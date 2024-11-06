<?php

namespace App\Http\Controllers;

use App\Http\Services\ReportService;
use App\Models\UserScoreView;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Traits\VerificaAccesoTrait;

class CursoController extends Controller
{
    use VerificaAccesoTrait;

    public function inscribirse($curso_id)
    {
        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para el curso
        $cursoCacheKey = 'curso_' . $curso_id;

        // Obtener o almacenar en caché el objeto del curso
        $curso = Cache::remember($cursoCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($curso_id) {
            return Curso::find($curso_id);
        });

        // Verificar que el objeto de curso es válido
        if (!$curso) {
            return redirect()->route('home')
                ->with('error', 'No se pudo encontrar el taller que deseas matricularte');
        }

        // Usar el trait para verificar solo las fechas de acceso al taller
        $resultado = $this->verificarFechasAcceso($curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar que el usuario no esté ya inscrito en el curso
        if (Inscripcion::where('curso_id', $curso_id)->where('user_id', Auth::id())->first()) {
            return redirect()->route('home')
                ->with('warning', 'Ya estás inscrito en este curso');
        }

        // Matricular al usuario en el curso
        $curso->users()->attach(Auth::user()->id);

        return redirect()->route('home')
            ->with('success', 'Te has inscrito en este curso ' . $curso->onombre);
    }
    // Metodo para show certificados
    public function showCertificado($cursoId)
    {
        $user = Auth::user();
        $curso = Curso::findOrFail($cursoId);

        // Generar los parámetros codificados
        $reportPath = parse_url($curso->ofile_path, PHP_URL_PATH);

        $hashids = Hashids::connection('main');
        $encodedParams = $hashids->encode($curso->id, $user->id);
        $url = route('certificados.verify', ['encodedParams' => $encodedParams]);

        $params = [
            'curso_id' => $curso->id,
            'user_id' => $user->id,
            'url' => $url,
        ];

        $filename = "{$user->orfc}.pdf";

        // Llamar al servicio de reporte
        $reportResponse = ReportService::generateReport($reportPath, $params, $filename);

        if ($reportResponse && $reportResponse['status'] === 'success') {
            // Devolver los datos codificados en base64
            return response()->json([
                'status' => $reportResponse['status'],
                'data' => $reportResponse['data']
            ]);
        } else {
            // Devolver un mensaje de error si no se pudo generar el reporte
            return response()->json([
                'status' => $reportResponse['status'],
                'message' => $reportResponse['message']
            ], 500);
        }
    }


    // Metodo para verificar certificados
    public function verifyCertificado($encodedParams)
    {
        try {
            // Decodificar los parámetros
            $hashids = Hashids::connection('main');
            $decoded = $hashids->decode($encodedParams);

            list($cursoId, $userId) = $decoded;

            // Buscar la información en la base de datos utilizando el modelo UserScoreView
            $userScore = UserScoreView::where('curso_id', $cursoId)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Retornar la vista de verificación con los datos obtenidos
            return view('certificados.verify', compact('userScore'));

        } catch (\Exception $e) {
            // Manejar errores
            return redirect()->route('home')
                ->with('error', 'Los parámetros proporcionados no son válidos.');
        }
    }
}

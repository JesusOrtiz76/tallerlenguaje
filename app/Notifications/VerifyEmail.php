<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends VerifyEmailBase
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $currentTime = Carbon::now();
        $salutation = $this->getSalutation($currentTime);

        return (new MailMessage)
            ->subject('Verificación de Correo Electrónico - Protocolo de Actuación de SEIEM') //asunto del correo
            ->greeting($salutation) // Bienvenida
            ->line('Por favor haga click en el botón para verificar su correo electrónico.') // primer renglón
            ->action('Verificar Correo Electrónico', $verificationUrl) // Botón
            ->line('Si no creó una cuenta, no se requiere ninguna otra acción.') // segundo renglón
            ->salutation('Gracias por su atención.'); // Saludo
    }

    /**
     * Get the salutation based on the current time.
     *
     * @param  Carbon  $currentTime
     * @return string
     */
    protected function getSalutation(Carbon $currentTime)
    {
        $hour = $currentTime->hour;

        if ($hour >= 5 && $hour < 12) {
            return '¡Buenos días!';
        } elseif ($hour >= 12 && $hour < 19) {
            return '¡Buenas tardes!';
        } else {
            return '¡Buenas noches!';
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}

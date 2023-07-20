<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = url(config('app.url').route('password.reset', $this->token, false));

        $currentTime = Carbon::now();
        $salutation = $this->getSalutation($currentTime);

        return (new MailMessage)
            ->subject('Restablecimiento de contraseña - Protocolo de Actuación de SEIEM') // Asunto
            ->greeting($salutation) // Bienvenida
            ->line('Ha recibido este mensaje porque se solicitó un restablecimiento de contraseña para su cuenta.')
            ->action('Restablecer contraseña', $url)
            ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos.')
            ->line('Si no ha solicitado el restablecimiento de contraseña, omita este mensaje de correo electrónico.')
            ->salutation('Gracias por su atención.');
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
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorCodeNotification extends Notification
{
    public function __construct(private string $code) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre code de vérification — TravailTogo')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Voici votre code de connexion à usage unique :')
            ->line('## ' . $this->code)
            ->line('Ce code est valable **5 minutes**.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email et votre compte restera sécurisé.')
            ->salutation('L\'équipe TravailTogo');
    }
}
<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable;

class Invitation extends Notification
{
    use Notifiable , Queueable;
    private $team, $token, $isNewUser;
    /**
     * Create a new notification instance.
     */
    public function __construct(Team $team, string $token, bool $isNewUser)
    {
        $this->team = $team;

        $this->token = $token;

        $this->isNewUser = $isNewUser;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Your are being invited in team'. $this->team->name)
                    ->action('Click here.', $this->generateUrl())
                    ->line('Thank you for using our application!');
    }

    /**
     * Generate the appropriate URL based on the user and token.
     *
     * @return string The generated URL.
     */
    private function generateUrl(): string 
    {
        return $this->isNewUser ? url(config('app.url').'/register?token='.$this->token) : url(config('app.url').'/teams/info/'.$this->team->id);
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

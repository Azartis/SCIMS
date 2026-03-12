<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected string $newStatus;

    public function __construct(string $newStatus)
    {
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // email plus store in the database so it can be shown within the app
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->newStatus);
        $message = "Your account status has been changed to {$status}.";
        if ($this->newStatus === 'inactive') {
            $message .= ' You will no longer be able to log in until an administrator reactivates your account.';
        } elseif ($this->newStatus === 'blocked') {
            $message .= ' Please contact an administrator for further assistance.';
        }

        return (new MailMessage)
            ->subject('Account Status Updated')
            ->line($message)
            ->action('Visit Dashboard', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->newStatus,
        ];
    }
}

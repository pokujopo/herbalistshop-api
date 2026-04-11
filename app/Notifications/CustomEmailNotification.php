<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomEmailNotification extends Notification
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class CustomEmailNotification extends Notification
{
    public $type;
    public $token;

    public function __construct($type = 'verify', $token = null)
    {
        $this->type = $type; // 'verify' or 'reset'
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = new MailMessage;

        $mail->greeting('Hello '.$notifiable->name);

        if($this->type === 'verify') {
            $url = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->email)]
            );
            $mail->subject('Confirm Your Email')
                 ->line('Thanks for registering! Please click the button below to verify your email.')
                 ->action('Verify Email', $url);
        } else if($this->type === 'reset') {
            $url = url('/reset-password/'.$this->token);
            $mail->subject('Reset Your Password')
                 ->line('You requested to reset your password. Click below to reset it.')
                 ->action('Reset Password', $url);
        }

        $mail->line('If you did not request this, no further action is needed.');

        return $mail;
    }
}    {
        return [
            //
        ];
    }
}

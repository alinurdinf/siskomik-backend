<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kutia\Larafirebase\Messages\FirebaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SendPushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $fcmTokens;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage)
            ->withTitle($this->title)
            ->withBody($this->message)
            ->withPriority('high')
            ->asMessage($this->fcmTokens);
    }
}
<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewLesson extends Notification implements ShouldQueue {
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Lesson $lesson) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        /*return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));*/

        return (new MailMessage())
            ->greeting('Приглашаем вас на новый урок!')
            ->line('Чтобы перейти нажмите на кнопку внизу.')
            ->action(
                'Перейти к уроку '.$this->lesson->number,
                route('skills.lesson.show', ['number' => $this->lesson->number]),
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}

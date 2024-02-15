<?php

namespace App\Listeners;

use App\Models\Subscriber;
use App\Events\LessonCreated;
use App\Notifications\NewLesson;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLessonCreatedNotifications implements ShouldQueue {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LessonCreated $event): void {
        foreach (Subscriber::whereIsEnabled(true)->cursor() as $subscriber) {
            $subscriber->notify(new NewLesson($event->lesson));
        }
    }
}

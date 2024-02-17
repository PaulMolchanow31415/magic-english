<?php

namespace App\Listeners;

use App\Models\Subscriber;
use App\Events\CourseCreated;
use App\Notifications\NewCourse;

class SendCourseCreatedNotification {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CourseCreated $event): void {
        foreach (Subscriber::whereIsEnabled(true)->cursor() as $subscriber) {
            $subscriber->notify(new NewCourse($event->course));
        }
    }
}

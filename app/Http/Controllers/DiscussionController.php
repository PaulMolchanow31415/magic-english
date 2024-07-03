<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Discussion;

class DiscussionController extends Controller {

    public function index() {
        return inertia('Admin/Discussion', [
            'discussions'      => Discussion::all(),
            'reportedComments' => Comment::whereIsReported(true)->get(),
        ]);
    }

    public function show(Discussion $discussion) {
        return $discussion;
    }

    public function destroy(Discussion $discussion) {
        $discussion->delete();
    }
}

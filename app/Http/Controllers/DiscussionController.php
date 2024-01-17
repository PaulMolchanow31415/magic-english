<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Comment;
use App\Models\Discussion;
use Inertia\ResponseFactory;

class DiscussionController extends Controller {

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Discussion', [
            'discussions'      => Discussion::all(),
            'reportedComments' => Comment::whereIsReported(true)->get(),
        ]);
    }

    public function show(string $routeName): array {
        $discussion = Discussion::firstOrCreate(['for_route_name' => $routeName]);

        return ['comments' => $discussion->comments];
    }

    public function destroy(int $id): void {
        Discussion::destroy($id);
    }
}

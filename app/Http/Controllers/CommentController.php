<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'discussion_id' => 'required|int',
            'reply_to_id'   => 'int|nullable',
            'message'       => 'required|string|min:1',
        ]);

        $comment = new Comment();
        $comment->message = $request['message'];
        $comment->creator()->associate(user());
        $comment->discussion()->associate($request['discussion_id']);

        if ($request->filled('reply_to_id')) {
            $comment->reply_to()->associate($request['reply_to_id']);
        }

        $comment->save();
    }

    public function report(Request $request) {
        $request->validate([
            'commentId'  => 'int|required',
            'isReported' => 'boolean|nullable',
        ]);

        Comment::findOrFail($request['commentId'])
            ->forceFill(['is_reported' => $request['isReported'] ?? true])
            ->save();
    }

    public function destroy(Comment $comment) {
        Gate::authorize('delete-comment', user());

        $comment->delete();
    }
}

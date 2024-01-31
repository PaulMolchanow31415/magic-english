<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Discussion;
use Illuminate\Http\Request;

class CommentController extends Controller {

    public function store(Request $request): void {
        $request->validate([
            'discussion_id' => 'required|int',
            'reply_to_id'   => 'int|nullable',
            'message'       => 'required|string|min:1',
        ]);

        $created = new Comment();
        $created->message = $request['message'];
        $created->creator()->associate(User::find(auth()->id()));
        $created->discussion()->associate($request['discussion_id']);

        if ($request->filled('reply_to_id')) {
            $created->reply_to()->associate($request['reply_to_id']);
        }

        $created->save();
    }

    public function report(Request $request): void {
        $request->validate([
            'commentId'  => 'int|required',
            'isReported' => 'boolean|nullable',
        ]);

        $reported = Comment::findOrFail($request['commentId']);
        $reported->is_reported = $request['isReported'] ?? true;
        $reported->save();
    }

    public function destroy(Comment $comment): void {
        if ($comment->creator_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();
    }
}

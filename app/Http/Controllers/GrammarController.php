<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use Illuminate\Http\Request;

class GrammarController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'id'        => 'int|nullable',
            'course_id' => 'int|required',
            'title'     => 'string|required|max:1024',
            'content'   => 'string|required',
            'order'     => 'int|required',
            'phonetics' => 'array|required',
        ]);

        $grammar = Grammar::updateOrCreate(['id' => $request['id']], [
            'title'     => $request['title'],
            'content'   => $request['content'],
            'order'     => $request['order'],
            'phonetics' => $request['phonetics'],
            'course_id' => $request['course_id'],
        ]);

        $grammar->course()->associate($request['course_id']);
        $grammar->save();
    }

    public function changeOrder(Request $request) {
        $request->validate([
            'items'         => 'array|required|min:1',
            'items.*.id'    => 'int|required',
            'items.*.order' => 'int|required|min:0',
        ]);

        foreach ($request['items'] as $item) {
            Grammar::whereId($item['id'])->update(['order' => $item['order']]);
        }
    }

    public function show(int $courseId) {
        return Grammar::whereCourseId($courseId)->get();
    }

    public function destroy(Grammar $grammar) {
        $grammar->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller {

    public function index() {
        return inertia('Admin/Faq', [
            'faqs' => Faq::all(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'id'      => 'int|nullable',
            'heading' => 'string|required|min:5|max:255',
            'content' => 'string|required|min:10',
        ]);

        Faq::updateOrCreate(['id' => $request['id']], [
            'heading' => $request['heading'],
            'content' => $request['content'],
        ]);

        return to_route('admin.faq.index');
    }

    public function destroy(Faq $faq) {
        $faq->delete();

        return to_route('admin.faq.index');
    }
}

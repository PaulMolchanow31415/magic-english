<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Http\RedirectResponse;

class FaqController extends Controller {

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Faq', [
            'faqs' => Faq::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'id'      => 'int|nullable',
            'heading' => 'string|required|min:5|max:255',
            'content' => 'string|required|min:10',
        ]);

        Faq::updateOrCreate(
            ['id' => $request['id']],
            [
                'heading' => $request['heading'],
                'content' => $request['content'],
            ],
        );

        return to_route('admin.faq.index');
    }

    public function destroy(Faq $faq): RedirectResponse {
        $faq->delete();

        return to_route('admin.faq.index');
    }
}

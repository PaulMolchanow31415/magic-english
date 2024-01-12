<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        // todo
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
        ]);

        Subscriber::create([
            'email' => $request['email'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscriber $subscriber) {
        // todo
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber) {
        // todo
    }
}

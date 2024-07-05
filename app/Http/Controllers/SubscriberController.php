<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;

class SubscriberController extends Controller {

    public function store(Request $request) {
        $request->validate(['email' => 'required|email|unique:subscribers']);
        Subscriber::create(['email' => $request->email]);
    }

    public function isSubscribed() {
        $subscriber = Subscriber::firstOrCreate([
            'email' => user()->email,
        ], ['is_enabled' => false]);

        return $subscriber->is_enabled;
    }

    public function changeSubscribingStatus(Request $request) {
        Subscriber::whereEmail(user()->email)->update([
            'is_enabled' => $request['status'] ?? false,
        ]);
    }

    public function index(SearchRequest $request) {
        return inertia('Admin/Subscriber', [
            'subscribers' => Subscriber::search($request['search'])->paginate(10),
            'filters'     => $request->only(['search']),
        ]);
    }

    public function destroy(Subscriber $subscriber) {
        $subscriber->delete();
    }
}

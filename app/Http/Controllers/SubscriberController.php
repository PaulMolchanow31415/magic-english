<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;

class SubscriberController extends Controller {

    public function store(Request $request): void {
        $request->validate(['email' => 'required|email|unique:subscribers']);
        Subscriber::create(['email' => $request['email']]);
    }

    public function isSubscribed(): int {
        // todo: extract to the method
        $subscriber = Subscriber::firstOrCreate([
            'email' => auth()->user()->email,
        ], ['is_enabled' => false]);

        return $subscriber->is_enabled;
    }

    // todo rename toggleSubscribingStatus
    public function changeSubscribeStatus(): void {
        Subscriber::whereEmail(auth()->user()->email)->update([
            'is_enabled' => request('status', false),
        ]);
    }

    public function index(Request $request): Response|ResponseFactory {
        return inertia('Admin/Subscriber', [
            'subscribers' => Subscriber::search($request['search'])->paginate(10),
            'filters'     => $request->only(['search']),
        ]);
    }

    public function destroy(Subscriber $subscriber): void {
        $subscriber->delete();
    }
}

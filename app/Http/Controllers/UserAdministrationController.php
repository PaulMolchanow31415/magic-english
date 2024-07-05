<?php

namespace App\Http\Controllers;

use App\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\SearchRequest;
use App\Actions\Jetstream\DeleteUser;

class UserAdministrationController extends Controller {

    public function index(SearchRequest $request) {
        return inertia('Admin/User', [
            'users' => User::search($request['search'])
                ->whereNotIn('id', [auth()->id()])
                ->paginate(5)
                ->through(fn(User $user) => [
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'profile_photo_url' => $user->profile_photo_url,
                    'role'              => $user->role,
                    'is_banned'         => $user->is_banned,
                ]),

            'filters' => $request->only(['search']),
            'roles' => Role::cases(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'id'        => ['int', 'required'],
            'role' => [Rule::enum(Role::class)],
            'is_banned' => ['boolean'],
        ]);

        User::findOrFail($request['id'])->forceFill([
            'role'      => $request['role'],
            'is_banned' => $request['is_banned'] ?? false,
        ])->save();
    }

    public function destroy(User $user, DeleteUser $action) {
        $action->delete($user);

        return to_route('admin.user.index');
    }
}

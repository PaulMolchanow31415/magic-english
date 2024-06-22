<?php

namespace App\Http\Controllers;

use App\Role;
use App\Models\User;
use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use App\Actions\Jetstream\DeleteUser;

class UserAdministrationController extends Controller {

    private function defineRole(?string $value): string {
        if (empty($value)) {
            return Role::ADMIN;
        } elseif ($value !== Role::USER and $value !== Role::ADMIN) {
            abort(400);
        }

        return $value;
    }

    public function index(Request $request): Response|ResponseFactory {
        return inertia('Admin/User', [
            'users' => User::search($request['search'])
                ->whereNotIn('id', [auth()->user()->id])
                ->paginate(5)
                ->through(function ($user) {
                    return [
                        'id'                => $user->id,
                        'name'              => $user->name,
                        'email'             => $user->email,
                        'profile_photo_url' => $user->profile_photo_url,
                        'role'              => $user->role,
                        'is_banned'         => $user->is_banned,
                    ];
                }),

            'filters' => $request->only(['search']),
            'roles'   => [Role::USER, Role::ADMIN],
        ]);
    }

    public function store(Request $request): void {
        $request->validate([
            'id'        => ['int', 'required'],
            'role'      => ['string'],
            'is_banned' => ['boolean'],
        ]);

        $user = User::findOrFail($request['id']);
        $user->role = $this->defineRole($request['role']);
        $user->is_banned = $request['is_banned'] ?? false;
        $user->save();
    }

    public function destroy(User $user, DeleteUser $action): RedirectResponse {
        $action->delete($user);

        return to_route('admin.user.index');
    }
}

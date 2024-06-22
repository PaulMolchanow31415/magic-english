<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Role;
use App\Models\Faq;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        User::factory(20)->create()->map(function (User $user) {
            return $user->cart()->save(
                Cart::create(['user_id' => $user->id]),
            );
        });

        $user = User::factory()->create([
            'name'  => 'Paul',
            'email' => 'pmolch3.1415@gmail.com',
            'role'  => Role::ADMIN,
        ]);

        $user->cart()->save(
            Cart::create(['user_id' => $user->id]),
        );

        Faq::factory(10)->create();
    }
}

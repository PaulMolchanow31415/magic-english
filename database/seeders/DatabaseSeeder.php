<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Faq;
use App\Models\Role;
use App\Models\User;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use App\Models\VocabularyCategory;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        User::factory(20)->create();

        User::factory()->create([
            'name'  => 'Paul',
            'email' => 'pmolch3.1415@gmail.com',
            'role'  => Role::ADMIN,
        ]);

        Faq::factory(10)->create();

        VocabularyCategory::factory(20)->create();

        CourseCategory::factory(20)->create();
    }
}

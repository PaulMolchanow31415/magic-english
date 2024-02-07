<?php

use App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('grammars', function (Blueprint $table) {
            $table->id();

            $table->string('title', 1024);
            $table->text('content');
            $table->smallInteger('order');
            $table->json('phonetics');

            $table->foreignIdFor(Course::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('grammars');
    }
};

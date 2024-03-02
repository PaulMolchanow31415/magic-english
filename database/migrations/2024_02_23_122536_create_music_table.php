<?php

use App\Models\AuthorSong;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('music', function (Blueprint $table) {
            $table->id();

            $table->string('name', 2048);

            $table->foreignIdFor(AuthorSong::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('audio_url', 2048)->nullable();
            $table->text('lyrics');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('music');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('en')->unique();
            $table->json('translations');
            $table->string('poster_url', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vocabularies');
    }
};

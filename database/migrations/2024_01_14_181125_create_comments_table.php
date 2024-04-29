<?php

use App\Models\Discussion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->boolean('is_reported')->default(false);

            $table->foreignId('creator_id')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('reply_to_id')
                ->nullable()
                ->references('id')->on('comments')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Discussion::class)
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
        Schema::dropIfExists('comments');
    }
};

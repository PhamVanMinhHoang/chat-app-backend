<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reaction_type', 20); // e.g., like, love, haha, wow, sad, angry

            $table->timestamps();

            $table->unique(['message_id', 'user_id']); // mỗi người chỉ được 1 reaction/tin
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};

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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->string('file_url');
            $table->string('file_type', 50); // e.g., image, video, document, audio
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};

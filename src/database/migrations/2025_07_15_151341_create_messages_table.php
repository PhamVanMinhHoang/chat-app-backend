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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('content')->nullable();    // nội dung văn bản
            $table->boolean('is_system')->default(false); // tin nhắn hệ thống (vd: user rời nhóm)
            $table->boolean('is_edited')->default(false); // có bị sửa không

            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->boolean('has_attachment')->default(false); // nếu có file đính kèm

            $table->timestamps(); // created_at = thời điểm gửi

            $table->index(['conversation_id', 'created_at']); // để lấy danh sách theo hội thoại nhanh
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

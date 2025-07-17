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
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('last_message_id')
                ->nullable()
                ->constrained('messages')
                ->onDelete('set null')
                ->after('updated_at');

            // Tạo index để sắp xếp nhanh
            $table->index('last_message_id', 'conversations_last_message_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['last_message_id']);
            $table->dropIndex('conversations_last_message_id_index');
            $table->dropColumn('last_message_id');
        });
    }
};

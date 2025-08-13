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
        Schema::create('report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('original_name');
            $table->string('file_name'); // Stored file name (hashed for security)
            $table->string('file_path');
            $table->string('mime_type');
            $table->integer('file_size'); // In bytes
            $table->string('file_hash')->nullable(); // For integrity checking
            $table->enum('attachment_type', ['document', 'image', 'video', 'audio', 'other'])->default('document');
            $table->text('description')->nullable();
            $table->boolean('is_evidence')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('report_id');
            $table->index(['attachment_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_attachments');
    }
};

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
        Schema::create('report_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin/Investigator
            $table->text('comment');
            $table->enum('comment_type', ['internal', 'status_change', 'request_info', 'resolution'])->default('internal');
            $table->enum('visibility', ['internal', 'reporter_visible'])->default('internal'); // Whether reporter can see this
            $table->json('status_change')->nullable(); // Store old/new status if it's a status change
            $table->boolean('is_system_generated')->default(false); // For automated comments
            $table->timestamps();
            
            // Indexes
            $table->index(['report_id', 'created_at']);
            $table->index('comment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_comments');
    }
};

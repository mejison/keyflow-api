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
        Schema::create('typing_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('wpm'); // Words per minute
            $table->decimal('accuracy', 5, 2); // Accuracy percentage (0-100)
            $table->integer('duration'); // Test duration in seconds
            $table->integer('correct_words')->default(0);
            $table->integer('incorrect_words')->default(0);
            $table->integer('total_words')->default(0);
            $table->text('text_content')->nullable(); // The text that was typed
            $table->timestamp('completed_at');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typing_tests');
    }
};

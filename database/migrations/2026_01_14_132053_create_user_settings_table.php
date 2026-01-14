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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Display settings
            $table->string('theme')->default('light'); // light, dark, system
            $table->string('language')->default('en'); // en, uk, etc
            $table->string('font_family')->default('monospace');
            $table->integer('font_size')->default(16);
            
            // Test settings
            $table->integer('test_duration')->default(60); // seconds
            $table->string('difficulty')->default('medium'); // easy, medium, hard
            $table->boolean('show_timer')->default(true);
            $table->boolean('show_wpm')->default(true);
            $table->boolean('show_accuracy')->default(true);
            
            // Sound settings
            $table->boolean('sound_enabled')->default(true);
            $table->boolean('key_sound')->default(true);
            $table->boolean('error_sound')->default(true);
            $table->integer('sound_volume')->default(50); // 0-100
            
            // Behavior settings
            $table->boolean('auto_save')->default(true);
            $table->boolean('show_keyboard')->default(false);
            $table->boolean('blind_mode')->default(false); // Hide text while typing
            $table->string('cursor_style')->default('block'); // block, line, underline
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};

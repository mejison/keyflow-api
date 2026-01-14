<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'font_family',
        'font_size',
        'test_duration',
        'difficulty',
        'show_timer',
        'show_wpm',
        'show_accuracy',
        'sound_enabled',
        'key_sound',
        'error_sound',
        'sound_volume',
        'auto_save',
        'show_keyboard',
        'blind_mode',
        'cursor_style',
    ];

    protected $casts = [
        'font_size' => 'integer',
        'test_duration' => 'integer',
        'show_timer' => 'boolean',
        'show_wpm' => 'boolean',
        'show_accuracy' => 'boolean',
        'sound_enabled' => 'boolean',
        'key_sound' => 'boolean',
        'error_sound' => 'boolean',
        'sound_volume' => 'integer',
        'auto_save' => 'boolean',
        'show_keyboard' => 'boolean',
        'blind_mode' => 'boolean',
        'cursor_style' => 'string',
    ];

    /**
     * Get default settings
     */
    public static function getDefaults(): array
    {
        return [
            'theme' => 'light',
            'language' => 'en',
            'font_family' => 'monospace',
            'font_size' => 16,
            'test_duration' => 30,
            'difficulty' => 'medium',
            'show_timer' => true,
            'show_wpm' => true,
            'show_accuracy' => true,
            'sound_enabled' => false,
            'key_sound' => true,
            'error_sound' => true,
            'sound_volume' => 50,
            'auto_save' => true,
            'show_keyboard' => false,
            'blind_mode' => false,
            'cursor_style' => 'block',
        ];
    }

    /**
     * Get the user that owns the settings
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

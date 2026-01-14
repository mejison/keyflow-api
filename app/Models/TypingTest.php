<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypingTest extends Model
{
    protected $fillable = [
        'user_id',
        'wpm',
        'accuracy',
        'duration',
        'correct_words',
        'incorrect_words',
        'total_words',
        'text_content',
        'completed_at',
    ];

    protected $casts = [
        'accuracy' => 'decimal:2',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the typing test
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

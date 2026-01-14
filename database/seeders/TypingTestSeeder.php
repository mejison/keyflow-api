<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TypingTest;
use Illuminate\Database\Seeder;

class TypingTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user to create tests for
        $user = User::where('email', 'newuser@example.com')->first();
        
        if (!$user) {
            $this->command->info('User not found. Creating sample user...');
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info("Creating typing tests for user: {$user->email}");

        // Create tests from most recent to oldest
        $tests = [
            ['wpm' => 75, 'accuracy' => 94.00, 'completed_at' => now()->subHours(2)],
            ['wpm' => 68, 'accuracy' => 91.00, 'completed_at' => now()->subHours(5)],
            ['wpm' => 82, 'accuracy' => 97.00, 'completed_at' => now()->subDay()],
            ['wpm' => 70, 'accuracy' => 89.50, 'completed_at' => now()->subDays(2)],
            ['wpm' => 78, 'accuracy' => 95.20, 'completed_at' => now()->subDays(3)],
            ['wpm' => 65, 'accuracy' => 88.00, 'completed_at' => now()->subDays(5)],
            ['wpm' => 72, 'accuracy' => 92.50, 'completed_at' => now()->subDays(7)],
            ['wpm' => 80, 'accuracy' => 96.00, 'completed_at' => now()->subDays(10)],
        ];

        foreach ($tests as $testData) {
            $duration = rand(30, 180);
            $totalWords = intval(($testData['wpm'] * $duration) / 60);
            $correctWords = intval($totalWords * ($testData['accuracy'] / 100));
            $incorrectWords = $totalWords - $correctWords;

            TypingTest::create([
                'user_id' => $user->id,
                'wpm' => $testData['wpm'],
                'accuracy' => $testData['accuracy'],
                'duration' => $duration,
                'correct_words' => $correctWords,
                'incorrect_words' => $incorrectWords,
                'total_words' => $totalWords,
                'text_content' => 'Sample typing test text...',
                'completed_at' => $testData['completed_at'],
            ]);
        }

        $this->command->info('Typing tests created successfully!');
    }
}


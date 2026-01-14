<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserSettingsController extends BaseController
{
    /**
     * Get user settings
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get or create settings with defaults
        $settings = $user->settings;
        
        if (!$settings) {
            $settings = UserSettings::create([
                'user_id' => $user->id,
                ...UserSettings::getDefaults()
            ]);
        }
        
        return $this->sendResponse([
            'settings' => $settings->only([
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
            ])
        ], 'Settings retrieved successfully');
    }
    
    /**
     * Update user settings
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'theme' => 'sometimes|in:light,dark,system',
            'language' => 'sometimes|string|max:10',
            'font_family' => 'sometimes|string|max:50',
            'font_size' => 'sometimes|integer|min:10|max:32',
            'test_duration' => 'sometimes|integer|min:15|max:300',
            'difficulty' => 'sometimes|in:easy,medium,hard',
            'show_timer' => 'sometimes|boolean',
            'show_wpm' => 'sometimes|boolean',
            'show_accuracy' => 'sometimes|boolean',
            'sound_enabled' => 'sometimes|boolean',
            'key_sound' => 'sometimes|boolean',
            'error_sound' => 'sometimes|boolean',
            'sound_volume' => 'sometimes|integer|min:0|max:100',
            'auto_save' => 'sometimes|boolean',
            'show_keyboard' => 'sometimes|boolean',
            'blind_mode' => 'sometimes|boolean',
            'cursor_style' => 'sometimes|in:block,line,underline',
        ]);
        
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors()->toArray(), 422);
        }
        
        // Get or create settings
        $settings = $user->settings;
        
        if (!$settings) {
            $settings = UserSettings::create([
                'user_id' => $user->id,
                ...UserSettings::getDefaults()
            ]);
        }
        
        // Update only provided fields
        $settings->update($validator->validated());
        
        return $this->sendResponse([
            'settings' => $settings->only([
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
            ])
        ], 'Settings updated successfully');
    }
    
    /**
     * Reset settings to defaults
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $settings = $user->settings;
        
        if ($settings) {
            $settings->update(UserSettings::getDefaults());
        } else {
            $settings = UserSettings::create([
                'user_id' => $user->id,
                ...UserSettings::getDefaults()
            ]);
        }
        
        return $this->sendResponse([
            'settings' => $settings->only([
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
            ])
        ], 'Settings reset to defaults successfully');
    }
}

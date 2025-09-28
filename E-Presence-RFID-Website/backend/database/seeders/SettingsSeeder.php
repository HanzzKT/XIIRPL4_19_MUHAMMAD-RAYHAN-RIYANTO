<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;
use App\Models\User;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create default settings if none exist
        if (!Settings::exists()) {
            $adminUser = User::where('username', 'adminproject')->first();
            
            Settings::create([
                'user_id' => $adminUser ? $adminUser->id : null,
                'service' => 'face_id',
                'api_key' => null,
                'connected_at' => now(),
                'attendance_method' => 'both', // Default to both RFID and Face ID
                'face_recognition_enabled' => true,
                'anti_spoofing_enabled' => true,
                'face_confidence_threshold' => 0.70
            ]);
        }
    }
}

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
        Schema::table('settings', function (Blueprint $table) {
            // Make user_id nullable if it exists
            if (Schema::hasColumn('settings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            
            $table->enum('attendance_method', ['rfid', 'face_id', 'both'])->default('rfid')->after('connected_at');
            $table->boolean('face_recognition_enabled')->default(false)->after('attendance_method');
            $table->boolean('anti_spoofing_enabled')->default(true)->after('face_recognition_enabled');
            $table->decimal('face_confidence_threshold', 3, 2)->default(0.70)->after('anti_spoofing_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_method',
                'face_recognition_enabled', 
                'anti_spoofing_enabled',
                'face_confidence_threshold'
            ]);
        });
    }
};

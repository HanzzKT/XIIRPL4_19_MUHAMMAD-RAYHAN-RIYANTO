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
        Schema::create('face_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->longText('face_data'); // Base64 encoded face data
            $table->longText('face_data_2')->nullable(); // Additional face sample
            $table->longText('face_data_3')->nullable(); // Additional face sample
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('anti_spoofing_score', 5, 4)->nullable(); // Anti-spoofing confidence
            $table->decimal('confidence_score', 5, 4)->nullable(); // Face detection confidence
            $table->timestamps();
            
            $table->unique('user_id'); // One registration per user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_registrations');
    }
};

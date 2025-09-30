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
        // Drop and recreate complaints table with all needed columns
        Schema::dropIfExists('complaints');
        
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('complaint_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description');
            $table->string('customer_phone')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->enum('source', ['phone', 'website', 'whatsapp'])->default('website');
            $table->text('action_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('cs_response')->nullable();
            $table->timestamp('cs_response_updated_at')->nullable();
            $table->boolean('feedback_read_by_customer')->default(false);
            $table->timestamp('feedback_read_at')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Escalation fields
            $table->unsignedBigInteger('escalation_to')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->text('escalation_reason')->nullable();
            $table->unsignedBigInteger('escalated_by')->nullable();
            $table->string('manager_action')->nullable();
            $table->text('manager_notes')->nullable();
            $table->timestamp('manager_action_at')->nullable();
            $table->unsignedBigInteger('manager_action_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};

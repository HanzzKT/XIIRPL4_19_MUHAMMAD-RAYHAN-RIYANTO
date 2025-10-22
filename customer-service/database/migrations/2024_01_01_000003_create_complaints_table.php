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
            $table->text('action_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('cs_response')->nullable();
            $table->timestamp('cs_response_updated_at')->nullable();
            
            // Escalation fields with foreign keys
            $table->foreignId('escalation_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('escalated_at')->nullable();
            $table->text('escalation_reason')->nullable();
            $table->foreignId('escalated_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Manager claim fields
            $table->foreignId('manager_claimed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('manager_claimed_at')->nullable();
            
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

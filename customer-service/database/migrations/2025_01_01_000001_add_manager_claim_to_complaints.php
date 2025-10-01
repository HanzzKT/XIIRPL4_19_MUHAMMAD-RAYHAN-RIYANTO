<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('manager_claimed_by')->nullable()->after('escalation_to')->constrained('users')->onDelete('set null');
            $table->timestamp('manager_claimed_at')->nullable()->after('manager_claimed_by');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['manager_claimed_by']);
            $table->dropColumn(['manager_claimed_by', 'manager_claimed_at']);
        });
    }
};

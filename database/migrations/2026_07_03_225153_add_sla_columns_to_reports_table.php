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
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('verified_deadline')->nullable()->after('status');
            $table->timestamp('handled_deadline')->nullable()->after('verified_deadline');
            $table->boolean('is_overdue')->default(false)->after('handled_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['verified_deadline', 'handled_deadline', 'is_overdue']);
        });
    }
};

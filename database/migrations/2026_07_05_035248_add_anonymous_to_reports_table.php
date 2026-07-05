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
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->boolean('is_anonymous')->default(false)->after('status');
            $table->string('anonymous_name')->nullable()->after('is_anonymous');
            $table->string('anonymous_contact')->nullable()->after('anonymous_name');
            $table->string('anonymous_code')->nullable()->unique()->after('anonymous_contact');
        });

        Schema::table('report_status_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_status_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable(false)->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn(['is_anonymous', 'anonymous_name', 'anonymous_contact', 'anonymous_code']);
        });
    }
};

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
        // Drop foreign key constraints before modifying columns (MySQL restriction safety)
        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignore on sqlite/testing if not exists
                }
            });
        }

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->boolean('is_anonymous')->default(false)->after('status');
            $table->string('anonymous_name')->nullable()->after('is_anonymous');
            $table->string('anonymous_contact')->nullable()->after('anonymous_name');
            $table->string('anonymous_code')->nullable()->unique()->after('anonymous_contact');
        });

        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                try {
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                } catch (\Exception $e) {
                }
            });
        }

        if (Schema::hasTable('report_status_logs')) {
            Schema::table('report_status_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['changed_by']);
                } catch (\Exception $e) {
                }
            });
        }

        Schema::table('report_status_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable()->change();
        });

        if (Schema::hasTable('report_status_logs')) {
            Schema::table('report_status_logs', function (Blueprint $table) {
                try {
                    $table->foreign('changed_by')->references('id')->on('users')->cascadeOnDelete();
                } catch (\Exception $e) {
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                }
            });
        }

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn(['is_anonymous', 'anonymous_name', 'anonymous_contact', 'anonymous_code']);
        });

        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                try {
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                } catch (\Exception $e) {
                }
            });
        }

        if (Schema::hasTable('report_status_logs')) {
            Schema::table('report_status_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['changed_by']);
                } catch (\Exception $e) {
                }
            });
        }

        Schema::table('report_status_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable(false)->change();
        });

        if (Schema::hasTable('report_status_logs')) {
            Schema::table('report_status_logs', function (Blueprint $table) {
                try {
                    $table->foreign('changed_by')->references('id')->on('users')->cascadeOnDelete();
                } catch (\Exception $e) {
                }
            });
        }
    }
};

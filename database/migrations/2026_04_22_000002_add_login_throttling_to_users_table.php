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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'login_attempts')) {
                $table->integer('login_attempts')->default(0)->after('is_blocked');
            }
            if (!Schema::hasColumn('users', 'blocked_until')) {
                $table->timestamp('blocked_until')->nullable()->after('login_attempts');
            }
            if (!Schema::hasColumn('users', 'last_failed_login_ip')) {
                $table->string('last_failed_login_ip')->nullable()->after('blocked_until');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['login_attempts', 'blocked_until', 'last_failed_login_ip'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

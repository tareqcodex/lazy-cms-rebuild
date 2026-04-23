<?php
/*
|--------------------------------------------------------------------------
| Consolidated Migration for Users Table Additions
|--------------------------------------------------------------------------
| This migration combines all CMS-related additions to the users table,
| including role association, blocking status, and login throttling.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role assignment
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->nullOnDelete();
            }
            
            // Blocking and throttling
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'login_attempts')) {
                $table->integer('login_attempts')->default(0)->after('is_blocked');
                $table->timestamp('last_attempt_at')->nullable()->after('login_attempts');
                $table->timestamp('blocked_until')->nullable()->after('last_attempt_at');
            }

            // Drop old role string column if exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'is_blocked', 'login_attempts', 'last_attempt_at', 'blocked_until']);
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('subscriber')->after('id');
            }
        });
    }
};

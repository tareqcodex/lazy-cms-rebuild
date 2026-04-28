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
        if (Schema::hasTable('comments') && !Schema::hasColumn('comments', 'is_read')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->boolean('is_read')->default(false)->after('is_approved');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'is_read')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
    }
};

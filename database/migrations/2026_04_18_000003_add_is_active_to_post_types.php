<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            if (!Schema::hasColumn('post_types', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_builtin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            if (Schema::hasColumn('post_types', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};

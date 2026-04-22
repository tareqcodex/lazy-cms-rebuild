<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            if (!Schema::hasColumn('post_types', 'show_in_menu')) {
                $table->boolean('show_in_menu')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('post_types', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('show_in_menu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            $table->dropColumn(['show_in_menu', 'is_public']);
        });
    }
};

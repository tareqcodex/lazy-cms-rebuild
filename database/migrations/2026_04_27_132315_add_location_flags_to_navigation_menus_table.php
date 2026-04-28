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
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->boolean('is_header')->default(false)->after('slug');
            $table->boolean('is_footer')->default(false)->after('is_header');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->dropColumn(['is_header', 'is_footer']);
        });
    }
};

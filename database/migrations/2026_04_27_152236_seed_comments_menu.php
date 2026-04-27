<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Comments menu
        DB::table('menus')->insert([
            'title' => 'Comments',
            'route' => 'admin.comments.index',
            'icon' => 'chat', // Material icon name
            'order' => 25,
            'group' => 'Main',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menus')->where('title', 'Comments')->delete();
    }
};

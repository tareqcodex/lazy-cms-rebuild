<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add published_at column to posts
        if (!Schema::hasColumn('posts', 'published_at')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });
        }

        // Fix Sidebar Order: Posts (2), Media (3), Pages (4)
        DB::table('menus')->where('title', 'Posts')->update(['order' => 2]);
        DB::table('menus')->where('title', 'Media')->update(['order' => 3]);
        DB::table('menus')->where('title', 'Pages')->update(['order' => 4]);
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};

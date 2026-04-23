<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            if (!Schema::hasColumn('post_types', 'singular_name')) {
                $table->string('singular_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('post_types', 'supports')) {
                $table->json('supports')->nullable()->after('is_builtin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('post_types', function (Blueprint $table) {
            if (Schema::hasColumn('post_types', 'singular_name')) {
                $table->dropColumn('singular_name');
            }
            if (Schema::hasColumn('post_types', 'supports')) {
                $table->dropColumn('supports');
            }
        });
    }
};

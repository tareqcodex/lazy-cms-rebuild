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
        if (Schema::hasTable('custom_taxonomies') && !Schema::hasColumn('custom_taxonomies', 'deleted_at')) {
            Schema::table('custom_taxonomies', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('custom_taxonomies')) {
            Schema::table('custom_taxonomies', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};

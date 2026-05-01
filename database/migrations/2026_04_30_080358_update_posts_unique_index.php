<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop old unique index
            $table->dropUnique('posts_slug_type_unique');
            
            // Add new unique index including lang_code
            $table->unique(['slug', 'type', 'lang_code'], 'posts_slug_type_lang_unique');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_type_lang_unique');
            $table->unique(['slug', 'type'], 'posts_slug_type_unique');
        });
    }
};

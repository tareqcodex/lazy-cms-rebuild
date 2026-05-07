<?php

namespace Acme\CmsDashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use Acme\CmsDashboard\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        Language::updateOrInsert(['code' => 'en'], ['name' => 'English', 'is_default' => true, 'status' => true]);
        Language::updateOrInsert(['code' => 'bn'], ['name' => 'Bengali', 'is_default' => false, 'status' => true]);
    }
}

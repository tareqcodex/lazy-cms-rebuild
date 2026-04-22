<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_cms_option')) {
    /**
     * Get a setting value from the cms_settings table.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_cms_option($key, $default = null)
    {
        try {
            $value = DB::table('cms_settings')->where('key', $key)->value('value');
            return $value !== null ? $value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

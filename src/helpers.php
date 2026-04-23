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

if (!function_exists('get_custom_field')) {
    /**
     * Get a custom field value for a post.
     *
     * @param mixed $post Post object or ID
     * @param string $fieldName
     * @param mixed $default
     * @return mixed
     */
    function get_custom_field($post, $fieldName, $default = null)
    {
        try {
            $postId = is_object($post) ? $post->id : $post;
            
            $value = DB::table('post_custom_field_values')
                ->join('custom_fields', 'post_custom_field_values.field_id', '=', 'custom_fields.id')
                ->where('post_custom_field_values.post_id', $postId)
                ->where('custom_fields.name', $fieldName)
                ->value('post_custom_field_values.value');

            return $value !== null ? $value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

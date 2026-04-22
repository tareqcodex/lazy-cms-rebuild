<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class TaxonomyTerm extends Model
{
    protected $table = 'taxonomy_terms';
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(TaxonomyTerm::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TaxonomyTerm::class, 'parent_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_taxonomy_term', 'taxonomy_term_id', 'post_id');
    }

    public static function generateUniqueSlug($name, $id = 0, $cptSlug = null)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($name));
            $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);
            $slug = mb_strtolower($slug);
        }
        $slug = $slug ?: 'term';

        $originalSlug = $slug;
        $count = 1;
        while (static::where('slug', $slug)->where('id', '!=', $id)->when($cptSlug, function($q) use ($cptSlug) {
            return $q->where('cpt_slug', $cptSlug);
        })->exists()) {
            $slug = "{$originalSlug}-" . ($count++);
        }
        return $slug;
    }
}

<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTaxonomy extends Model
{
    protected $table = 'custom_taxonomies';

    protected $guarded = [];

    protected $casts = [
        'post_types' => 'array',
        'is_active' => 'boolean',
        'hierarchical' => 'boolean',
    ];

    public function terms()
    {
        return $this->hasMany(TaxonomyTerm::class, 'taxonomy_slug', 'slug');
    }
}

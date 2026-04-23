<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'description'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id ?? 0);
            }
        });
    }

    public static function generateUniqueSlug($name, $id = 0)
    {
        $slug = Str::slug($name);
        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($name));
            $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);
            $slug = mb_strtolower($slug);
        }
        $slug = $slug ?: 'category';

        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function parent(): BelongsTo { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(Category::class, 'parent_id'); }
    public function posts(): BelongsToMany { return $this->belongsToMany(Post::class); }

    public function getPathAttribute()
    {
        $path = $this->slug;
        $parent = $this->parent;
        while ($parent) {
            $path = $parent->slug . '/' . $path;
            $parent = $parent->parent;
        }
        return $path;
    }
}

<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = static::generateUniqueSlug($tag->name, $tag->id ?? 0);
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
        $slug = $slug ?: 'tag';

        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function posts(): BelongsToMany { return $this->belongsToMany(Post::class); }
}

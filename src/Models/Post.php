<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    
    // Fallback to avoid breaking if project has User namespace issues currently
    // protected $fillable = ['user_id', 'title', 'slug', 'content', 'type', 'status'];
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_meta' => 'array',
    ];

    /**
     * Calculate SEO score percentage (0-100)
     */
    public function getSeoScore(): int
    {
        $meta = is_array($this->seo_meta) ? $this->seo_meta : [];
        $score = 0;

        // Title: 30% (Fallback to post title if meta title is empty)
        $title = !empty($meta['title']) ? $meta['title'] : ($this->title ?? '');
        if (!empty($title)) {
            $len = strlen($title);
            if ($len >= 50 && $len <= 60) $score += 30;
            elseif ($len > 0) $score += 15;
        }

        // Description: 40%
        if (!empty($meta['description'])) {
            $len = strlen($meta['description']);
            if ($len >= 150 && $len <= 160) $score += 40;
            elseif ($len > 0) $score += 20;
        }

        // Keywords: 10%
        if (!empty($meta['keywords'])) $score += 10;

        // OG Image: 20%
        if (!empty($meta['og_image'])) $score += 20;

        return $score;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereHas('user', function($q) {
                         $q->where('is_blocked', false);
                     });
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function postTypeDefinition(): BelongsTo
    {
        return $this->belongsTo(PostType::class, 'type', 'slug');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function taxonomyTerms(): BelongsToMany
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'post_taxonomy_term', 'post_id', 'taxonomy_term_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->where('is_approved', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    public const CATEGORIES = [
        'POLITIK & PEMERINTAHAN',
        'WISATA & LINGKUNGAN',
        'SOSIAL',
        'PENDIDIKAN',
        'EKONOMI',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'thumbnail',
        'thumbnail_data',
        'thumbnail_mime',
        'published_at',
    ];

    protected $hidden = [
        'thumbnail_data',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeWithoutThumbnailData(Builder $query): Builder
    {
        return $query->select([
            'id',
            'user_id',
            'title',
            'slug',
            'category',
            'excerpt',
            'content',
            'thumbnail',
            'thumbnail_mime',
            'published_at',
            'created_at',
            'updated_at',
        ]);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (filled($this->thumbnail_mime)) {
            return route('news.thumbnail', [
                'news' => $this->slug,
                'v' => $this->updated_at?->timestamp ?? 1,
            ]);
        }

        if (! $this->thumbnail) {
            return null;
        }

        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        return Storage::url($this->thumbnail);
    }

    public function getSummaryAttribute(): string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        return Str::limit(trim(strip_tags($this->content)), 160);
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'berita';
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn (Builder $query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

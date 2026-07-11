<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    /**
     * Daftar kategori artikel yang valid.
     * Gunakan konstanta ini di Form Request, seeder, dan view.
     */
    public const CATEGORIES = [
        'Daur Ulang',
        'Regulasi',
        'Tips Lingkungan',
        'Edukasi',
        'Inspirasi',
    ];

    protected $fillable = [
        'author_id', 'title', 'slug', 'category', 'image_url', 'content', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 120);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200));
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}

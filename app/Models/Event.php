<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /**
     * Daftar kategori event yang valid.
     */
    public const CATEGORIES = [
        'Bersih-bersih',
        'Tanam Pohon',
        'Gotong Royong',
        'Edukasi',
        'Pengolahan Sampah',
        'Umum',
    ];

    protected $fillable = [
        'organizer_id', 'report_id', 'title', 'description',
        'location', 'latitude', 'longitude',
        'banner_url', 'category', 'event_date', 'max_participants',
    ];

    protected $casts = [
        'event_date'       => 'datetime',
        'latitude'         => 'decimal:7',
        'longitude'        => 'decimal:7',
        'max_participants' => 'integer',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class)->with('user');
    }

    public function activeParticipants(): HasMany
    {
        return $this->hasMany(EventParticipant::class)->where('status', 'registered')->with('user');
    }

    public function isUpcoming(): bool
    {
        return $this->event_date->isFuture();
    }

    public function isFull(): bool
    {
        if (is_null($this->max_participants)) return false;
        return $this->activeParticipants()->count() >= $this->max_participants;
    }

    public function spotsRemaining(): ?int
    {
        if (is_null($this->max_participants)) return null;
        return max(0, $this->max_participants - $this->activeParticipants()->count());
    }

    public function userParticipant(?int $userId): ?EventParticipant
    {
        if (!$userId) return null;
        return $this->participants()->where('user_id', $userId)->first();
    }
}

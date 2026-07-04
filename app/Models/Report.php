<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'volunteer_id',
        'title',
        'description',
        'address',
        'latitude',
        'longitude',
        'status',
        'after_photo_url',
        'verified_deadline',
        'handled_deadline',
        'is_overdue',
    ];

    protected function casts(): array
    {
        return [
            'latitude'          => 'decimal:7',
            'longitude'         => 'decimal:7',
            'verified_deadline' => 'datetime',
            'handled_deadline'  => 'datetime',
            'is_overdue'        => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($report) {
            $report->verified_deadline = now()->addHours(48);
        });

        static::updating(function ($report) {
            if ($report->isDirty('status') && $report->status === 'verified') {
                $report->handled_deadline = now()->addDays(7);
            }
        });
    }

    // ── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class);
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class)->orderBy('created_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function getStatusColorClass(): string
    {
        return match ($this->status) {
            'pending'     => 'status-pending',
            'verified'    => 'status-verified',
            'in_progress' => 'status-in-progress',
            'resolved'    => 'status-resolved',
            'rejected'    => 'status-rejected',
            default       => 'status-pending',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'     => 'Menunggu',
            'verified'    => 'Terverifikasi',
            'in_progress' => 'Diproses',
            'resolved'    => 'Selesai',
            'rejected'    => 'Ditolak',
            default       => 'Menunggu',
        };
    }

    public function getSlaVerificationAttribute()
    {
        if (in_array($this->status, ['verified', 'in_progress', 'resolved'])) {
            return ['status' => 'completed', 'label' => 'Terverifikasi'];
        }
        if (!$this->verified_deadline) {
            return ['status' => 'waiting', 'label' => 'Menunggu Batas Waktu'];
        }
        if (now()->gt($this->verified_deadline)) {
            $diff = now()->diff($this->verified_deadline);
            return [
                'status' => 'overdue',
                'label' => 'Terlambat ' . $diff->days . ' hari ' . $diff->h . ' jam',
                'percent' => 100
            ];
        }
        $total = $this->created_at->diffInMinutes($this->verified_deadline);
        $elapsed = $this->created_at->diffInMinutes(now());
        $percent = $total > 0 ? min(100, max(0, round(($elapsed / $total) * 100))) : 0;
        
        $diff = now()->diff($this->verified_deadline);
        return [
            'status' => 'on_time',
            'label' => 'Sisa ' . $diff->days . ' hari ' . $diff->h . ' jam',
            'percent' => $percent
        ];
    }

    public function getSlaHandlingAttribute()
    {
        if ($this->status === 'resolved') {
            return ['status' => 'completed', 'label' => 'Selesai Tepat Waktu'];
        }
        if (!$this->handled_deadline) {
            return ['status' => 'waiting', 'label' => 'Menunggu Verifikasi'];
        }
        if (now()->gt($this->handled_deadline)) {
            $diff = now()->diff($this->handled_deadline);
            return [
                'status' => 'overdue',
                'label' => 'Terlambat ' . $diff->days . ' hari',
                'percent' => 100
            ];
        }
        
        $verifiedLog = $this->statusLogs()->where('new_status', 'verified')->first();
        $verifiedAt = $verifiedLog ? $verifiedLog->created_at : $this->created_at;

        $total = $verifiedAt->diffInMinutes($this->handled_deadline);
        $elapsed = $verifiedAt->diffInMinutes(now());
        $percent = $total > 0 ? min(100, max(0, round(($elapsed / $total) * 100))) : 0;
        
        $diff = now()->diff($this->handled_deadline);
        return [
            'status' => 'on_time', 
            'label' => 'Sisa ' . $diff->days . ' hari',
            'percent' => $percent
        ];
    }
}

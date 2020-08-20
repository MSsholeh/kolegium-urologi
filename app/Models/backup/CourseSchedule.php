<?php

namespace App\Models;

use App\Helpers\Daster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CourseSchedule extends Model
{
    use SoftDeletes;

    protected $with = ['admin', 'requirements'];
    protected $guarded = ['id'];
    protected $dates  = [
        'started_at',
        'ended_at',
        'published_at'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->admin_id = Auth::user()->id;
        });

        static::updating(static function ($model) {
            $model->admin_id = Auth::user()->id;
        });
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function requirements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseRequirement::class);
    }

    public function participants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseParticipant::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function university(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /* Get Attributes */

    public function getEventDateFormatAttribute()
    {
        return Daster::tanggal($this->started_at, 1).' - '.Daster::tanggal($this->ended_at, 1);
    }


    /* Function */

    public function countRegistrant(): int
    {
        return $this->participants()->count();
    }

    public function countParticipant(): int
    {
        return $this->participants()->whereIn('status', ['Baru', 'Ditolak', 'Diterima'])->count();
    }

    public function countApprovedParticipant(): int
    {
        return $this->participants()->where('status', 'Diterima')->count();
    }

    public function getPublishedInDays(): string
    {
        $end = $this->created_at->diffInDays(now(), false);
        return $end < 0 ? abs($end).' hari lagi' : abs($end).' hari yang lalu';
    }

    public function getEndRemaining()
    {
        return $this->started_at->diffInDays(now(), false);
    }

    public function getEndInDays(): string
    {
        $end = $this->getEndRemaining();
        return $end < 0 ? abs($end).' hari lagi' : abs($end).' hari yang lalu';
    }

    public function getRemainingQuota()
    {
        $quota = $this->quota;
        $approved = $this->countApprovedParticipant();
        return $quota - $approved;
    }

    public function getRemainingQuotaPercent()
    {
        $quota = $this->quota;
        $approved = $this->countApprovedParticipant();

        return ($approved / $quota) * 100;
    }
}

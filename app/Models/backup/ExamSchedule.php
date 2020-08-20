<?php

namespace App\Models\backup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Daster;

class ExamSchedule extends Model
{
    use SoftDeletes;

    protected $with = ['admin', 'requirements'];
    protected $guarded = ['id'];
    protected $dates  = [
        'event_date',
        'published_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->admin_id = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->admin_id = Auth::user()->id;
        });
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function period(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function participants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ExamParticipant');
    }

    public function requirements()
    {
        return $this->hasMany(ExamRequirement::class);
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function university(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /* Get Attributes */

    public function getEventDateFormatAttribute()
    {
        return Daster::tanggal($this->event_date, 2);
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
        return $this->event_date->diffInDays(now(), false);
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

        return $quota > 0 ? ($approved / $quota) * 100 : 0;
    }
}

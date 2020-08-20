<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use SoftDeletes;

    protected $with = ['admin'];
    protected $guarded = ['id'];

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

    public function schedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }
}

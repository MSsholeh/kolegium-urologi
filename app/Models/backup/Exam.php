<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Exam extends Model
{
    use SoftDeletes;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['admin'];
    protected $guarded = ['id'];

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

    /* Relations */

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function schedule()
    {
        return $this->hasMany('App\Models\ExamSchedule');
    }
}

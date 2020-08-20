<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function participants()
    {
        return $this->hasMany(ExamParticipant::class);
    }
}

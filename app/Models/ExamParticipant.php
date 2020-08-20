<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamParticipant extends Model
{
    protected $guarded = ['id'];

    public function registrant()
    {
        return $this->belongsTo(Registrant::class);
    }

    public function registrant_graduation()
    {
        return $this->belongsTo(RegistrantGraduation::class);
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }
}

<?php

namespace App\Models\backup;

use Illuminate\Database\Eloquent\Model;

class ExamParticipant extends Model
{
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function requirements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ParticipantExamRequirement::class, 'exam_participant_id');
    }

    public function getGraduateStatus()
    {
        $graduate = $this->graduate === true ? 'Lulus' : 'Tidak Lulus';
        return  ($this->graduate === null ? '' : $graduate);
    }
}

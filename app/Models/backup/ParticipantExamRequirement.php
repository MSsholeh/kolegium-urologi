<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantExamRequirement extends Model
{
    protected $guarded = ['id'];

    public function requirement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExamRequirement::class, 'exam_requirement_id');
    }
}

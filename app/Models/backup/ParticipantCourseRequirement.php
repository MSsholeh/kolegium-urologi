<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantCourseRequirement extends Model
{
    protected $guarded = ['id'];

    public function requirement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseRequirement::class, 'course_requirement_id');
    }
}

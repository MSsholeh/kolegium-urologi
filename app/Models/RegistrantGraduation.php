<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrantGraduation extends Model
{
    protected $table = 'registrants_graduation';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function requirements_graduation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantRequirementGraduation::class);
    }

    public function requirement_graduation()
    {
        return $this->belongsTo(RequirementGraduation::class);
    }

    public function participate()
    {
        return $this->hasOne(ExamParticipant::class);
    }
}

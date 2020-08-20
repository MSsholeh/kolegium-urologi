<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrant extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function requirements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantRequirement::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    public function participate()
    {
        return $this->hasOne(ExamParticipant::class);
    }
}

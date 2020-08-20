<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrantCertificate extends Model
{
    protected $table = 'registrants_certificate';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requirements_certificate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantRequirementCertificate::class);
    }

    public function requirement_certificate()
    {
        return $this->belongsTo(RequirementCertificate::class);
    }

    public function participate()
    {
        return $this->hasOne(ExamParticipant::class);
    }
}

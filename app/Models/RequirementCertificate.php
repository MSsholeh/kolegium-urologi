<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequirementCertificate extends Model
{
    protected $table = 'requirements_certificate';
    protected $guarded = ['id'];

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function university(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequirementCertificateItems::class);
    }

    public function registrants_certificate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantCertificate::class);
    }
}

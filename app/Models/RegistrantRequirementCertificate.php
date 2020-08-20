<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrantRequirementCertificate extends Model
{
    protected $table = 'registrant_requirements_certificate';
    protected $guarded = ['id'];

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RequirementCertificateItems::class, 'requirement_certificate_item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrantRequirementGraduation extends Model
{
    protected $table = 'registrant_requirements_graduation';
    protected $guarded = ['id'];

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RequirementGraduationItems::class, 'requirement_graduation_item_id');
    }
}

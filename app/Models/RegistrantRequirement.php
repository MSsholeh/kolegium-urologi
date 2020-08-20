<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrantRequirement extends Model
{
    protected $guarded = ['id'];

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RequirementItems::class, 'requirement_item_id');
    }
}

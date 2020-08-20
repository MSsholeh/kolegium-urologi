<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
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
        return $this->hasMany(RequirementItems::class);
    }

    public function registrants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Registrant::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}

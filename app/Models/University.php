<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $guarded = ['id'];

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function requirements_graduation()
    {
        return $this->hasMany(RequirementGraduation::class);
    }

    public function registrant(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Registrant::class);
    }

    public function registrant_graduation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantGraduation::class);
    }

    public function availableRequirement()
    {
        return $this->requirements()->with('items')
            ->where('status', 'Active')
            ->first();
    }

    public function availableRequirementGraduation()
    {
        return $this->requirements_graduation()->with('items')
            ->where('status', 'Active')
            ->first();
    }
}

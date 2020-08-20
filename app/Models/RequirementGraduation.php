<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequirementGraduation extends Model
{
    protected $table = 'requirements_graduation';
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
        return $this->hasMany(RequirementGraduationItems::class);
    }

    public function registrants_graduation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantGraduation::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}

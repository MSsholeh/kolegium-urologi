<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $guarded = ['id'];

    public function scopeRegistration($query) {
        return $query->where('type', 'registration');
    }

    public function scopeExam($query) {
        return $query->where('type', 'exam');
    }
}

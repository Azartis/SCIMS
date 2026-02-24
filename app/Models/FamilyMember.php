<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyMember extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'senior_citizen_id',
        'name',
        'relationship',
        'age',
        'civil_status',
        'occupation',
        'monthly_income',
        'address',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
    ];

    /**
     * Get the senior citizen that this family member belongs to
     */
    public function seniorCitizen()
    {
        return $this->belongsTo(SeniorCitizen::class);
    }
}

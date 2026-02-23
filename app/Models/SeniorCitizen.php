<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeniorCitizen extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'fullname',
        'date_of_birth',
        'age',
        'gender',
        'address',
        'barangay',
        'contact_number',
        'osca_id',
        'sss',
        'gsis',
        'pvao',
        'family_pension',
        'brgy_official',
        'waitlist',
        'social_pension',
        'remarks',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'sss' => 'boolean',
        'gsis' => 'boolean',
        'pvao' => 'boolean',
        'family_pension' => 'boolean',
        'brgy_official' => 'boolean',
        'waitlist' => 'boolean',
        'social_pension' => 'boolean',
    ];

    /**
     * Get the formatted display name (e.g., "Rosita M. Miano")
     */
    public function getFormattedDisplayName()
    {
        $name = $this->firstname;
        
        if ($this->middlename) {
            $name .= ' ' . substr($this->middlename, 0, 1) . '.';
        }
        
        $name .= ' ' . $this->lastname;
        
        return $name;
    }
}

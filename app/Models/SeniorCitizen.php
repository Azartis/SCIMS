<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable;

class SeniorCitizen extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        // Personal / Basic Information
        'firstname',
        'middlename',
        'lastname',
        'extension_name',
        'fullname',
        'date_of_birth',
        'place_of_birth',
        'age',
        'sex',
        'civil_status',
        'citizenship',
        'religion',
        'educational_attainment',
        'address',
        'barangay',
        'contact_number',
        'osca_id',
        
        // Health Condition Section
        'with_disability',
        'type_of_disability',
        'bedridden',
        'with_assistive_device',
        'type_of_assistive_device',
        'with_critical_illness',
        'specify_illness',
        'philhealth_member',
        'philhealth_id',
        
        // Source of Income Section
        'is_pensioner',
        'pension_type',
        'monthly_pension_amount',
        'other_income_source',
        'total_monthly_income',
        
        // Classification
        'is_indigent',
        'age_range',
        
        // Legacy Fields
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
        'with_disability' => 'boolean',
        'bedridden' => 'boolean',
        'with_assistive_device' => 'boolean',
        'with_critical_illness' => 'boolean',
        'philhealth_member' => 'boolean',
        'is_pensioner' => 'boolean',
        'is_indigent' => 'boolean',
        'monthly_pension_amount' => 'decimal:2',
        'total_monthly_income' => 'decimal:2',
    ];

    /**
     * Get age - automatically calculated from date_of_birth
     * This ensures age is always current and increments on each birthday
     */
    public function getAgeAttribute($value)
    {
        if ($this->date_of_birth) {
            $age = now()->diffInYears($this->date_of_birth);
            return max(0, $age); // Ensure age is never negative
        }
        return $value ?? 0;
    }

    /**
     * Get age_range - automatically calculated from computed age
     * Always up-to-date based on current age
     */
    public function getAgeRangeAttribute($value)
    {
        $age = $this->age; // Uses the getAgeAttribute accessor
        
        if ($age >= 60 && $age < 70) {
            return '60-69';
        } elseif ($age >= 70 && $age < 80) {
            return '70-79';
        } elseif ($age >= 80) {
            return '80+';
        }
        
        return $value; // Return stored value if age doesn't fit ranges
    }

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

    /**
     * Get the family members for this senior citizen
     */
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    /**
     * Calculate total family income (senior + all family members)
     */
    public function getTotalFamilyIncome()
    {
        $seniorIncome = $this->total_monthly_income ?? 0;
        $familyIncome = $this->familyMembers()->sum('monthly_income');
        return $seniorIncome + $familyIncome;
    }

    /**
     * Auto-compute age from date of birth
     */
    public function calculateAge()
    {
        if ($this->date_of_birth) {
            $birthDate = $this->date_of_birth;
            $today = now();
            $age = $today->diffInYears($birthDate);
            
            // Ensure age is never negative
            $age = max(0, $age);
            
            // Set age range
            if ($age >= 60 && $age < 70) {
                $this->age_range = '60-69';
            } elseif ($age >= 70 && $age < 80) {
                $this->age_range = '70-79';
            } elseif ($age >= 80) {
                $this->age_range = '80+';
            }
            
            $this->age = $age;
        }
    }
}

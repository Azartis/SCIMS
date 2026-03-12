<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable;
use Carbon\Carbon;

class SeniorCitizen extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    /**
     * Prevent computed properties from being saved to the database
     */
    protected $guarded = [
        '_computed_age',
        '_computed_age_range',
    ];

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
        'national_id',

        // death information (for archived records)
        'date_of_death',
        'cause_of_death',
        'death_certificate_number',
        
        // Health Condition Section
        'with_disability',
        'type_of_disability',
        'cause_of_disability',
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
        'source_of_income',
        'other_income_source',
        'other_income_source_specify',
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
        'date_of_death' => 'date',
    ];

    /**
     * Get age - automatically calculated from date_of_birth
     * Cached in _computed_age to avoid recalculation during a single request
     */
    public function getAgeAttribute($value)
    {
        // Return cached value if already computed in this request
        if (isset($this->_computed_age)) {
            return $this->_computed_age;
        }

        // Try to obtain a Carbon instance for date_of_birth.
        $dob = $this->getAttributeValue('date_of_birth');

        // If casting didn't produce a Carbon instance, try parsing raw attribute
        if (!($dob instanceof Carbon) && isset($this->attributes['date_of_birth'])) {
            $raw = trim($this->attributes['date_of_birth']);
            if ($raw && $raw !== '0000-00-00') {
                try {
                    $dob = Carbon::parse($raw);
                } catch (\Exception $e) {
                    $dob = null;
                }
            }
        }

        if ($dob instanceof Carbon) {
            // If birthdate is in the future, treat age as 0
            if ($dob->isFuture()) {
                $this->_computed_age = 0;
            } else {
                // compute age from the date of birth toward the current time
                // using the birthdate as the starting point so that the result
                // is always non-negative even if Carbon's diffInYears defaults
                // to a signed value. (see bug where now()->diffInYears($dob)
                // returned a negative float on Windows/PHP 8+).
                $this->_computed_age = (int) $dob->diffInYears(now());
                // alternatively: now()->diffInYears($dob, true) would also work,
                // but using the birthdate makes the intention clearer.
            }

            return max(0, $this->_computed_age);
        }

        // Fallback to stored value or zero
        return $value ?? 0;
    }

    /**
     * Exact age string including years and months for accurate display.
     */
    public function getExactAgeAttribute()
    {
        $dob = $this->getAttributeValue('date_of_birth');
        if ($dob instanceof Carbon) {
            $diff = $dob->diff(now());
            $years = $diff->y;
            $months = $diff->m;
            $text = $years . ' yr';
            if ($years !== 1) {
                $text .= 's';
            }
            if ($months > 0) {
                $text .= ' ' . $months . ' mo';
                if ($months !== 1) {
                    $text .= 's';
                }
            }
            return $text;
        }

        return (string) ($this->age ?? '0');
    }

    /**
     * Helper for CSV/list exports – the month-day when the record was created.
     */
    public function getIssuanceDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('m-d') : '';
    }

    /**
     * Get age_range - optimized mapping from computed age
     * Cached to avoid recalculation during a single request
     */
    public function getAgeRangeAttribute($value)
    {
        if (isset($this->_computed_age_range)) {
            return $this->_computed_age_range;
        }

        $age = (int) $this->age; // Uses cached getAgeAttribute accessor

        // Use match expression for cleaner, faster logic
        $this->_computed_age_range = match (true) {
            $age >= 80 => '80+',
            $age >= 70 => '70-79',
            $age >= 60 => '60-69',
            default => $value ?? null,
        };

        return $this->_computed_age_range;
    }

    /**
     * Get the formatted display name for listings and headers.
     *
     * Old format used first name then last name with an initial; the
     * requirement now is to display the last name first, followed by a
     * comma and the first name and full middle name if present.  This
     * makes it easier to scan lists and match the CSV exports.
     *
     * Example: "Calupas, Ace Ziegfred" instead of "Ace Z. Calupas".
     */
    public function getFormattedDisplayName()
    {
        $parts = [];
        if ($this->lastname) {
            $parts[] = $this->lastname;
        }

        // build second segment: firstname + middlename
        $second = trim($this->firstname ?? '');
        if ($this->middlename) {
            $second .= ' ' . $this->middlename;
        }

        if ($second !== '') {
            $parts[] = $second;
        }

        return implode(', ', $parts);
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
     * Auto-compute age and age_range (used by controller when persisting)
     * This method sets both attributes so they are stored in the database for
     * backward compatibility with existing filters that query DB columns.
     */
    public function calculateAge()
    {
        $age = $this->age; // uses accessor which will compute and cache

        // Ensure integer and non-negative
        $age = max(0, (int) $age);

        // Determine age range
        if ($age >= 80) {
            $range = '80+';
        } elseif ($age >= 70) {
            $range = '70-79';
        } elseif ($age >= 60) {
            $range = '60-69';
        } else {
            $range = null;
        }

        // Store values on the model so save() will persist them
        $this->attributes['age'] = $age;
        $this->attributes['age_range'] = $range;

        // Also set cached copies for the current request
        $this->_computed_age = $age;
        $this->_computed_age_range = $range;

        return $this;
    }

    /**
     * Override setAttribute to prevent computed cache properties from being saved to DB
     */
    public function setAttribute($key, $value)
    {
        // Block internal computed cache properties from reaching the database
        if (in_array($key, ['_computed_age', '_computed_age_range'])) {
            // Store in object directly (for in-memory caching) but not in attributes array
            $this->{$key} = $value;
            return $this;
        }

        // Allow all other attributes through normal path
        return parent::setAttribute($key, $value);
    }

    /**
     * Get pension distributions for this senior citizen
     */
    public function pensiondistributions()
    {
        return $this->hasMany(PensionDistribution::class, 'senior_citizen_id');
    }

    /**
     * Get audit logs for this senior citizen
     */
    public function auditLogs()
    {
        return \App\Models\AuditLog::where('auditable_type', get_class($this))
            ->where('auditable_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent audit logs (last N changes)
     */
    public function recentAuditLogs($limit = 10)
    {
        return $this->auditLogs()->take($limit);
    }

    // ============ SCOPES FOR BETTER QUERY ORGANIZATION ============

    /**
     * Scope: Filter by social pension status
     */
    public function scopeBySocialPension($query, $status = true)
    {
        return $query->where('social_pension', $status);
    }

    /**
     * Scope: Filter by waitlist status
     */
    public function scopeByWaitlist($query, $status = true)
    {
        return $query->where('waitlist', $status);
    }

    /**
     * Scope: Filter by disability status
     */
    public function scopeByDisability($query, $status = true)
    {
        return $query->where('with_disability', $status);
    }

    /**
     * Scope: Filter by barangay
     */
    public function scopeByBarangay($query, $barangay)
    {
        return $query->where('barangay', $barangay);
    }

    /**
     * Scope: Filter by age range
     */
    public function scopeByAgeRange($query, $range)
    {
        return match($range) {
            '60-69' => $query->whereBetween('age', [60, 69]),
            '70-79' => $query->whereBetween('age', [70, 79]),
            '80-89' => $query->whereBetween('age', [80, 89]),
            '90+' => $query->where('age', '>=', 90),
            default => $query,
        };
    }

    /**
     * Scope: Filter by sex/gender
     */
    public function scopeBySex($query, $sex)
    {
        return $query->where('sex', $sex);
    }

    /**
     * Scope: Only active (non-deleted) records
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PensionDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'senior_citizen_id',
        'disbursement_date',
        'amount',
        'status',
        'authorized_rep_name',
        'authorized_rep_relationship',
        'authorized_rep_contact',
        'claimed_at',
        'notes',
    ];

    protected $casts = [
        'disbursement_date' => 'date',
        'amount' => 'decimal:2',
        'claimed_at' => 'datetime',
    ];

    public function seniorCitizen()
    {
        return $this->belongsTo(SeniorCitizen::class);
    }

    /**
     * Determine if the senior (even if deceased) is eligible for this distribution.
     * Business rule: deceased seniors remain eligible if their date_of_death is
     * on or after the start of the quarter containing the disbursement_date.
     */
    public function isSeniorEligible()
    {
        $senior = $this->seniorCitizen;
        if (! $senior) {
            return false;
        }

        // if no date_of_death recorded then eligible
        if (empty($senior->date_of_death)) {
            return true;
        }

        $d = $this->disbursement_date;
        if (! $d) {
            return false;
        }

        // compute quarter start
        $month = (int) $d->format('m');
        $q = (int) ceil($month / 3);
        $quarterStartMonth = ($q - 1) * 3 + 1;
        $quarterStart = \Carbon\Carbon::create($d->format('Y'), $quarterStartMonth, 1)->startOfDay();

        return $senior->date_of_death->greaterThanOrEqualTo($quarterStart);
    }
}

<?php

namespace App\Imports;

use App\Models\SeniorCitizen;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SeniorCitizenImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Basic required columns: osca_id, lastname, firstname, sex, barangay, date_of_birth
        $citizen = SeniorCitizen::firstOrNew([
            'osca_id' => $row['osca_id'] ?? null,
        ]);

        $citizen->lastname = $row['lastname'] ?? $citizen->lastname;
        $citizen->firstname = $row['firstname'] ?? $citizen->firstname;
        $citizen->middlename = $row['middlename'] ?? $citizen->middlename;
        $citizen->sex = $row['sex'] ?? $citizen->sex;
        $citizen->barangay = $row['barangay'] ?? $citizen->barangay;
        $citizen->address = $row['address'] ?? $citizen->address;
        $citizen->contact_number = $row['contact_number'] ?? $citizen->contact_number;

        if (! empty($row['date_of_birth'])) {
            $citizen->date_of_birth = Carbon::parse($row['date_of_birth']);
        }

        $citizen->save();

        return $citizen;
    }

    public function rules(): array
    {
        return [
            '*.osca_id' => ['required', 'string', 'max:255'],
            '*.lastname' => ['required', 'string', 'max:255'],
            '*.firstname' => ['required', 'string', 'max:255'],
            '*.sex' => ['required', 'in:Male,Female'],
            '*.barangay' => ['required', 'string', 'max:255'],
            '*.date_of_birth' => ['required', 'date'],
        ];
    }
}


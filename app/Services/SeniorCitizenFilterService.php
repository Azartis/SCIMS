<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

/**
 * Service for filtering senior citizen records
 * Provides consistent, reusable filtering logic across the application
 */
class SeniorCitizenFilterService
{
    /**
     * Apply age range or exact age filter to a query
     * 
     * Supports:
     * - Range format: "60-69" -> ages 60 to 69
     * - Range format: "70-79" -> ages 70 to 79
     * - Special format: "80+" -> ages 80 and above
     * - Exact age: "75" -> exactly age 75
     * - Exact age: "81" -> exactly age 81
     * 
     * @param Builder $query
     * @param string|null $ageRange The age range or exact age to filter by
     * @return Builder
     */
    public static function filterByAge(Builder $query, ?string $ageRange): Builder
    {
        if (empty($ageRange)) {
            return $query;
        }

        $ageRange = trim($ageRange);

        // Try to parse range format: "60-69"
        if (preg_match('/^(\d+)-(\d+)$/', $ageRange, $matches)) {
            $min = (int)$matches[1];
            $max = (int)$matches[2];
            return $query->whereBetween('age', [$min, $max]);
        }

        // Handle "80+" special case
        if ($ageRange === '80+') {
            return $query->where('age', '>=', 80);
        }

        // Handle "90+" special case (if needed in future)
        if ($ageRange === '90+') {
            return $query->where('age', '>=', 90);
        }

        // Handle exact age: "75" or "81"
        if (is_numeric($ageRange)) {
            $age = (int)$ageRange;
            if ($age >= 0 && $age <= 150) { // Sanity check
                return $query->where('age', $age);
            }
        }

        // If filter doesn't match any pattern, return unmodified query
        return $query;
    }

    /**
     * Get predefined age range options for dropdowns/suggestions
     * 
     * @return array
     */
    public static function getAgeRangeOptions(): array
    {
        return [
            '60-69' => '60-69 years old',
            '70-79' => '70-79 years old',
            '80-89' => '80-89 years old',
            '90-99' => '90-99 years old',
            '80+' => '80 years and older',
            '90+' => '90 years and older',
        ];
    }

    /**
     * Validate age range input
     * 
     * @param string $input
     * @return bool
     */
    public static function isValidAgeInput(string $input): bool
    {
        $input = trim($input);

        // Check if it's a valid range format
        if (preg_match('/^(\d+)-(\d+)$/', $input, $matches)) {
            $min = (int)$matches[1];
            $max = (int)$matches[2];
            return $min < $max && $min >= 0 && $max <= 150;
        }

        // Check special cases
        if (in_array($input, ['80+', '90+'])) {
            return true;
        }

        // Check exact age
        if (is_numeric($input)) {
            $age = (int)$input;
            return $age >= 0 && $age <= 150;
        }

        return false;
    }

    /**
     * Get human-readable description of age filter
     * 
     * @param string $ageRange
     * @return string
     */
    public static function getAgeDescription(string $ageRange): string
    {
        $ageRange = trim($ageRange);

        if (preg_match('/^(\d+)-(\d+)$/', $ageRange, $matches)) {
            return "ages {$matches[1]} to {$matches[2]}";
        }

        if ($ageRange === '80+') {
            return "ages 80 and older";
        }

        if ($ageRange === '90+') {
            return "ages 90 and older";
        }

        if (is_numeric($ageRange)) {
            return "age " . (int)$ageRange;
        }

        return "unknown age range";
    }
}

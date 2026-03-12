<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Advanced Filtering Service
 * 
 * Provides a unified approach to filtering across the entire system.
 * - Handles multiple filter types (text, select, range, date, boolean)
 * - Supports complex nested conditions
 * - Provides filter metadata and UI hints
 * - Maintains filter state for persistence
 */
class FilterService
{
    protected Builder $query;
    protected Request $request;
    protected array $filters = [];
    protected array $activeFilters = [];
    protected array $filterMetadata = [];

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    /**
     * Register a text search filter across multiple fields
     * 
     * @param string $parameterName - Form parameter name
     * @param array $searchFields - Database columns to search
     * @param array $metadata - UI metadata (label, placeholder, etc)
     */
    public function textSearch(string $parameterName, array $searchFields, array $metadata = []): self
    {
        $this->filters[$parameterName] = [
            'type' => 'text',
            'fields' => $searchFields,
            'value' => $this->request->input($parameterName),
        ];

        $this->filterMetadata[$parameterName] = array_merge([
            'label' => ucfirst($parameterName),
            'placeholder' => 'Search...',
            'icon' => '🔍',
        ], $metadata);

        if ($this->request->filled($parameterName)) {
            $search = $this->request->input($parameterName);
            $this->activeFilters[$parameterName] = $search;

            $this->query->where(function ($q) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        return $this;
    }

    /**
     * Register a select (dropdown) filter
     * 
     * @param string|null $column - Database column name (can be null for custom filters)
     */
    public function select(string $parameterName, ?string $column = null, array $options = [], array $metadata = []): self
    {
        $this->filters[$parameterName] = [
            'type' => 'select',
            'column' => $column,
            'options' => $options,
            'value' => $this->request->input($parameterName),
        ];

        $this->filterMetadata[$parameterName] = array_merge([
            'label' => ucfirst($parameterName),
            'placeholder' => 'Select...',
            'icon' => '📋',
        ], $metadata);

        if ($this->request->filled($parameterName) && $column !== null) {
            $value = $this->request->input($parameterName);
            $this->activeFilters[$parameterName] = $value;
            $this->query->where($column, $value);
        }

        return $this;
    }

    /**
     * Register a boolean/checkbox filter (Yes/No)
     */
    public function boolean(string $parameterName, string $column, array $metadata = []): self
    {
        $this->filters[$parameterName] = [
            'type' => 'boolean',
            'column' => $column,
            'value' => $this->request->input($parameterName),
        ];

        $this->filterMetadata[$parameterName] = array_merge([
            'label' => ucfirst($parameterName),
            'icon' => '✓',
        ], $metadata);

        if ($this->request->filled($parameterName)) {
            $value = $this->request->input($parameterName);
            $this->activeFilters[$parameterName] = $value;

            if ($value === '1' || $value === 'true') {
                $this->query->where($column, true);
            } elseif ($value === '0' || $value === 'false') {
                $this->query->where($column, false);
            }
        }

        return $this;
    }

    /**
     * Register a date range filter
     */
    public function dateRange(string $parameterNameFrom, string $parameterNameTo, string $column, array $metadata = []): self
    {
        $this->filters["date_range_{$column}"] = [
            'type' => 'dateRange',
            'column' => $column,
            'fromParam' => $parameterNameFrom,
            'toParam' => $parameterNameTo,
            'fromValue' => $this->request->input($parameterNameFrom),
            'toValue' => $this->request->input($parameterNameTo),
        ];

        $this->filterMetadata["date_range_{$column}"] = array_merge([
            'label' => ucfirst(str_replace('_', ' ', $column)),
            'icon' => '📅',
        ], $metadata);

        if ($this->request->filled($parameterNameFrom)) {
            $from = $this->request->input($parameterNameFrom);
            $this->query->whereDate($column, '>=', $from);
            $this->activeFilters[$parameterNameFrom] = $from;
        }

        if ($this->request->filled($parameterNameTo)) {
            $to = $this->request->input($parameterNameTo);
            $this->query->whereDate($column, '<=', $to);
            $this->activeFilters[$parameterNameTo] = $to;
        }

        return $this;
    }

    /**
     * Register a numeric range filter (e.g., age, amount)
     */
    public function numericRange(string $parameterNameMin, string $parameterNameMax, string $column, array $metadata = []): self
    {
        $this->filters["numeric_range_{$column}"] = [
            'type' => 'numericRange',
            'column' => $column,
            'minParam' => $parameterNameMin,
            'maxParam' => $parameterNameMax,
            'minValue' => $this->request->input($parameterNameMin),
            'maxValue' => $this->request->input($parameterNameMax),
        ];

        $this->filterMetadata["numeric_range_{$column}"] = array_merge([
            'label' => ucfirst(str_replace('_', ' ', $column)),
            'icon' => '📊',
        ], $metadata);

        if ($this->request->filled($parameterNameMin)) {
            $min = (int) $this->request->input($parameterNameMin);
            $this->query->where($column, '>=', $min);
            $this->activeFilters[$parameterNameMin] = $min;
        }

        if ($this->request->filled($parameterNameMax)) {
            $max = (int) $this->request->input($parameterNameMax);
            $this->query->where($column, '<=', $max);
            $this->activeFilters[$parameterNameMax] = $max;
        }

        return $this;
    }

    /**
     * Register a custom filter with a closure
     */
    public function custom(string $parameterName, \Closure $callback, array $metadata = []): self
    {
        $this->filterMetadata[$parameterName] = array_merge([
            'label' => ucfirst($parameterName),
        ], $metadata);

        if ($this->request->filled($parameterName)) {
            $value = $this->request->input($parameterName);
            $this->activeFilters[$parameterName] = $value;
            $callback($this->query, $value, $this->request);
        }

        return $this;
    }

    /**
     * Get the filtered query builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Get active filters (those with values)
     */
    public function getActiveFilters(): array
    {
        return $this->activeFilters;
    }

    /**
     * Get all registered filters
     */
    public function getAllFilters(): array
    {
        return $this->filters;
    }

    /**
     * Get filter metadata for UI rendering
     */
    public function getFilterMetadata(): array
    {
        return $this->filterMetadata;
    }

    /**
     * Check if any filters are currently active
     */
    public function hasActiveFilters(): bool
    {
        return !empty($this->activeFilters);
    }

    /**
     * Get count of active filters
     */
    public function getActiveFilterCount(): int
    {
        return count($this->activeFilters);
    }

    /**
     * Get reset URL (removes all filter parameters)
     */
    public function getResetUrl(string $currentRoute): string
    {
        return route($currentRoute);
    }

    /**
     * Get current filter state as array (for form persistence)
     */
    public function getCurrentState(): array
    {
        return array_map(fn($key) => $this->request->input($key), array_keys($this->activeFilters));
    }
}

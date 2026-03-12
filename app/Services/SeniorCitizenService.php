<?php

namespace App\Services;

use App\Models\SeniorCitizen;
use App\Models\AuditLog;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Senior Citizen Service
 * 
 * Handles all business logic for senior citizen management:
 * - CRUD operations
 * - Filtering and searching
 * - Validation and business rules
 * - Status management
 * - Cache invalidation
 * 
 * @package App\Services
 */
class SeniorCitizenService extends BaseService
{
    public function __construct(
        private CacheService $cache
    ) {}

    /**
     * Get all seniors with pagination
     * 
     * @param int $perPage
     * @param array $filters
     * @return Paginator
     */
    public function getAllPaginated(int $perPage = 20, array $filters = []): Paginator
    {
        $cacheKey = $this->cache->buildKey('seniors_paginated', $filters, request('page', 1));

        return $this->cache->rememberWithTag(
            'seniors',
            $cacheKey,
            CacheService::TTL['short'],
            fn() => $this->applyFilters($filters)->paginate($perPage)
        );
    }

    /**
     * Get all seniors without pagination (use with caution on large datasets!)
     * 
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        return $this->applyFilters($filters)
            ->with(['pensionDistributions', 'familyMembers'])
            ->get();
    }

    /**
     * Get a single senior citizen
     * 
     * @param int $id
     * @return SeniorCitizen
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(int $id): SeniorCitizen
    {
        return SeniorCitizen::with([
            'pensionDistributions',
            'familyMembers',
            'auditLogs'
        ])->findOrFail($id);
    }

    /**
     * Create a new senior citizen
     * 
     * @param array $data
     * @return SeniorCitizen
     */
    public function create(array $data): SeniorCitizen
    {
        try {
            $senior = SeniorCitizen::create($data);
            
            // Log activity
            AuditLog::create([
                'activity' => 'created',
                'model_name' => 'SeniorCitizen',
                'model_id' => $senior->id,
                'user_id' => auth()->id(),
                'old_values' => [],
                'new_values' => $data,
            ]);

            // Invalidate cache
            $this->cache->invalidateTag('seniors');

            $this->log("Senior citizen created: ID {$senior->id}");
            return $senior;
        } catch (\Exception $e) {
            $this->log("Error creating senior citizen: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    /**
     * Update a senior citizen
     * 
     * @param int $id
     * @param array $data
     * @return SeniorCitizen
     */
    public function update(int $id, array $data): SeniorCitizen
    {
        try {
            $senior = SeniorCitizen::findOrFail($id);
            $oldValues = $senior->toArray();

            $senior->update($data);

            // Log activity - only log changed fields
            $changedFields = array_diff_assoc($data, $oldValues);
            if (!empty($changedFields)) {
                AuditLog::create([
                    'activity' => 'updated',
                    'model_name' => 'SeniorCitizen',
                    'model_id' => $senior->id,
                    'user_id' => auth()->id(),
                    'old_values' => array_intersect_key($oldValues, $changedFields),
                    'new_values' => $changedFields,
                ]);
            }

            // Invalidate cache
            $this->cache->invalidateTag('seniors');

            $this->log("Senior citizen updated: ID {$senior->id}");
            return $senior;
        } catch (\Exception $e) {
            $this->log("Error updating senior citizen {$id}: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    /**
     * Delete (soft) a senior citizen
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $senior = SeniorCitizen::findOrFail($id);
            
            AuditLog::create([
                'activity' => 'deleted',
                'model_name' => 'SeniorCitizen',
                'model_id' => $senior->id,
                'user_id' => auth()->id(),
                'old_values' => $senior->toArray(),
                'new_values' => [],
            ]);

            $deleted = $senior->delete();

            if ($deleted) {
                $this->cache->invalidateTag('seniors');
                $this->log("Senior citizen deleted: ID {$id}");
            }

            return $deleted;
        } catch (\Exception $e) {
            $this->log("Error deleting senior citizen {$id}: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    /**
     * Restore a soft-deleted senior citizen
     * 
     * @param int $id
     * @return SeniorCitizen
     */
    public function restore(int $id): SeniorCitizen
    {
        $senior = SeniorCitizen::onlyTrashed()->findOrFail($id);
        $senior->restore();

        AuditLog::create([
            'activity' => 'restored',
            'model_name' => 'SeniorCitizen',
            'model_id' => $senior->id,
            'user_id' => auth()->id(),
            'old_values' => [],
            'new_values' => $senior->toArray(),
        ]);

        $this->cache->invalidateTag('seniors');
        $this->log("Senior citizen restored: ID {$id}");

        return $senior;
    }

    /**
     * Mark a senior as deceased
     * 
     * @param int $id
     * @param string $dateOfDeath
     * @param string $causeOfDeath
     * @param string|null $certificateNumber
     * @return SeniorCitizen
     */
    public function markAsDeceased(
        int $id,
        string $dateOfDeath,
        string $causeOfDeath,
        ?string $certificateNumber = null
    ): SeniorCitizen
    {
        $senior = SeniorCitizen::findOrFail($id);

        $senior->update([
            'date_of_death' => $dateOfDeath,
            'cause_of_death' => $causeOfDeath,
            'death_certificate_registration_number' => $certificateNumber,
            'remarks' => 'Deceased',
        ]);

        AuditLog::create([
            'activity' => 'marked_deceased',
            'model_name' => 'SeniorCitizen',
            'model_id' => $senior->id,
            'user_id' => auth()->id(),
            'old_values' => ['remarks' => $senior->getOriginal('remarks')],
            'new_values' => ['remarks' => 'Deceased', 'date_of_death' => $dateOfDeath],
        ]);

        $this->cache->invalidateTag('seniors');
        $this->log("Senior marked as deceased: ID {$id}");

        return $senior;
    }

    /**
     * Apply filters to query
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters(array $filters = [])
    {
        $query = SeniorCitizen::with('pensionDistributions')
            ->whereNull('deleted_at');

        // Search
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('id_number', '=', $search);
            });
        }

        // Barangay filter
        if (isset($filters['barangay']) && !empty($filters['barangay'])) {
            $query->where('barangay', $filters['barangay']);
        }

        // Status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            match ($filters['status']) {
                'alive' => $query->whereNull('date_of_death'),
                'deceased' => $query->where(function ($q) {
                    $q->whereNotNull('date_of_death')
                      ->orWhere('remarks', 'Deceased');
                }),
                'social_pension' => $query->whereHas('pensionDistributions', function ($q) {
                    $q->where('status', 'claimed');
                }),
                'waitlist' => $query->where('remarks', 'On Waitlist'),
                default => null,
            };
        }

        // Classification filter
        if (isset($filters['classification']) && !empty($filters['classification'])) {
            $query->where('classification', $filters['classification']);
        }

        // Disability filter
        if (isset($filters['disability']) && $filters['disability'] !== '') {
            $query->where('with_disability', (bool) $filters['disability']);
        }

        // Gender filter
        if (isset($filters['gender']) && !empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query;
    }

    /**
     * Get statistics about seniors
     * 
     * @return array
     */
    public function getStatistics(): array
    {
        $total = SeniorCitizen::count();
        $deceased = SeniorCitizen::where('date_of_death', '!=', null)->count();

        return [
            'total' => $total,
            'alive' => $total - $deceased,
            'deceased' => $deceased,
            'with_disability' => SeniorCitizen::where('with_disability', true)->count(),
            'social_pension' => SeniorCitizen::whereHas('pensionDistributions')->count(),
            'on_waitlist' => SeniorCitizen::where('remarks', 'On Waitlist')->count(),
        ];
    }

    /**
     * Bulk update senior citizens
     * 
     * @param array $ids
     * @param array $data
     * @return int
     */
    public function bulkUpdate(array $ids, array $data): int
    {
        $count = SeniorCitizen::whereIn('id', $ids)->update($data);

        if ($count > 0) {
            $this->cache->invalidateTag('seniors');
            $this->log("Bulk updated {$count} senior citizens");
        }

        return $count;
    }

    /**
     * Bulk delete seniors
     * 
     * @param array $ids
     * @return int
     */
    public function bulkDelete(array $ids): int
    {
        $count = SeniorCitizen::whereIn('id', $ids)->delete();

        if ($count > 0) {
            $this->cache->invalidateTag('seniors');
            $this->log("Bulk deleted {$count} senior citizens");
        }

        return $count;
    }

    /**
     * Export seniors to array (for CSV/Excel)
     * 
     * @param array $filters
     * @return Collection
     */
    public function export(array $filters = []): Collection
    {
        return $this->applyFilters($filters)
            ->select([
                'id',
                'id_number',
                'first_name',
                'last_name',
                'age',
                'gender',
                'barangay',
                'classification',
                'with_disability',
                'remarks',
                'date_of_death',
                'created_at'
            ])
            ->get()
            ->map(fn($senior) => [
                'ID Number' => $senior->id_number,
                'Name' => "{$senior->first_name} {$senior->last_name}",
                'Age' => $senior->age,
                'Gender' => $senior->gender,
                'Barangay' => $senior->barangay,
                'Classification' => $senior->classification,
                'Disability' => $senior->with_disability ? 'Yes' : 'No',
                'Status' => $senior->date_of_death ? 'Deceased' : 'Alive',
                'Remarks' => $senior->remarks,
                'Date of Death' => $senior->date_of_death,
                'Created' => $senior->created_at->format('Y-m-d'),
            ]);
    }
}

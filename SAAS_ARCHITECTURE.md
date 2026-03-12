# SaaS Enterprise Architecture for OSCAS

## Executive Summary

This document defines a production-ready SaaS architecture for the Online Senior Citizens Assessment System (OSCAS). The system is designed to handle 10k–50k+ records with clean separation of concerns, optimal performance, and enterprise-grade scalability.

---

## 1. Folder Structure

```
app/
├── Console/
│   └── Commands/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                       # API controllers (future)
│   │   │   ├── SeniorCitizenController.php
│   │   │   └── ReportController.php
│   │   ├── Dashboard/
│   │   │   └── DashboardController.php
│   │   ├── SeniorCitizens/
│   │   │   ├── SeniorCitizenController.php
│   │   │   ├── BulkController.php
│   │   │   └── ExportController.php
│   │   ├── Reports/
│   │   │   ├── ReportController.php
│   │   │   └── AnalyticsController.php
│   │   ├── Admin/
│   │   │   ├── UserController.php
│   │   │   ├── AuditLogController.php
│   │   │   └── SettingsController.php
│   │   ├── BaseController.php         # Shared controller logic
│   │   └── AuthController.php
│   ├── Middleware/
│   │   ├── Admin.php
│   │   ├── Staff.php
│   │   └── LogActivity.php
│   └── Requests/
│       └── SeniorCitizenRequest.php
├── Services/                           # CRITICAL: Business logic here
│   ├── DashboardService.php
│   ├── SeniorCitizenService.php
│   ├── ReportService.php
│   ├── AnalyticsService.php
│   ├── ExportService.php
│   ├── CacheService.php
│   └── BaseService.php
├── Repositories/                       # Data access layer
│   ├── BaseRepository.php
│   ├── SeniorCitizenRepository.php
│   ├── PensionDistributionRepository.php
│   ├── AuditLogRepository.php
│   └── Contracts/
│       ├── RepositoryInterface.php
│       └── SeniorCitizenRepositoryInterface.php
├── Models/
│   ├── SeniorCitizen.php
│   ├── PensionDistribution.php
│   ├── AuditLog.php
│   ├── User.php
│   └── Traits/
│       ├── HasTimestamps.php
│       ├── HasUUID.php
│       ├── Loggable.php
│       └── Filterable.php
├── Constants/
│   ├── Barangay.php
│   ├── Remarks.php
│   ├── PensionStatus.php
│   ├── UserRole.php
│   └── CacheTags.php
├── Exceptions/
│   ├── SeniorCitizenNotFoundException.php
│   ├── InvalidPensionStatusException.php
│   └── UnauthorizedException.php
└── Enums/                              # PHP 8.1+ Enums
    ├── UserRoleEnum.php
    ├── PensionStatusEnum.php
    └── ActivityTypeEnum.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php              # Main layout
│   │   ├── sidebar.blade.php          # Sidebar navigation
│   │   ├── nav.blade.php              # Top navigation
│   │   ├── auth.blade.php             # Auth layout
│   │   └── minimal.blade.php          # For modals/panels
│   ├── components/                    # Reusable components
│   │   ├── card.blade.php
│   │   ├── table.blade.php
│   │   ├── table-row.blade.php
│   │   ├── form-field.blade.php
│   │   ├── form-fieldset.blade.php
│   │   ├── button.blade.php
│   │   ├── badge.blade.php
│   │   ├── alert.blade.php
│   │   ├── modal.blade.php
│   │   ├── slide-over.blade.php
│   │   ├── pagination.blade.php
│   │   ├── loading-spinner.blade.php
│   │   ├── empty-state.blade.php
│   │   ├── kpi-card.blade.php
│   │   ├── chart-wrapper.blade.php
│   │   └── action-dropdown.blade.php
│   ├── dashboard/
│   │   └── index.blade.php            # New SaaS dashboard
│   ├── senior-citizens/
│   │   ├── index.blade.php            # List (SaaS table)
│   │   ├── show.blade.php
│   │   ├── create.blade.php           # Form (SaaS form)
│   │   ├── edit.blade.php
│   │   └── bulk-import.blade.php
│   ├── reports/
│   │   ├── index.blade.php
│   │   ├── health.blade.php
│   │   ├── barangay.blade.php
│   │   └── classification.blade.php
│   └── admin/
│       ├── users/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── audit-logs/
│           └── index.blade.php

database/
├── migrations/
│   ├── [existing migrations]
│   ├── 2024_01_01_add_indexes_for_performance.php
│   └── 2024_01_02_add_api_tokens_table.php
├── seeders/
└── factories/

config/
├── services.php                       # Cache config, API config
├── cache.php
├── database.php
└── audit.php                          # Audit logging config

storage/
├── cache/
│   └── [cache data]
└── logs/

tests/
├── Unit/
│   ├── Services/
│   ├── Repositories/
│   └── Models/
├── Feature/
│   ├── Controllers/
│   ├── Services/
│   └── Api/
└── Pest.php
```

---

## 2. Architecture Layers

### Layer 1: Controller Layer (HTTP Entry Points)
**Responsibility:** Handle HTTP requests, delegate to services, return responses.

```
User Request → Route → Controller → Service → Repository → Model → DB
                                ↓
                             Blade/JSON
```

**Controller Rules:**
- NO database queries
- NO business logic
- Delegate to services
- Validate input using Form Requests
- Return views/JSON from services
- Log user actions

### Layer 2: Service Layer (Business Logic)
**Responsibility:** Encapsulate all business logic, data transformation, calculations.

Services include:
- `DashboardService`: Metrics, aggregations, caching
- `SeniorCitizenService`: CRUD logic, validations, filters
- `ReportService`: Report generation, exports
- `AnalyticsService`: Aggregations, trends
- `ExportService`: CSV/Excel/PDF generation
- `CacheService`: Centralized cache management

**Service Rules:**
- Receive data from controller
- Use repositories for DB access
- Apply business rules
- Transform data for view
- Handle caching
- Return DTOs or arrays

### Layer 3: Repository Layer (Data Access)
**Responsibility:** Abstract database queries, provide clean query interface.

Repositories:
- `BaseRepository`: Common CRUD operations
- `SeniorCitizenRepository`: Senior citizen queries
- `PensionDistributionRepository`: Pension queries
- etc.

**Repository Rules:**
- Accept parameters, return collections/models
- Implement eager loading
- Handle pagination
- Apply filters
- Use query builder, not raw queries

### Layer 4: Model Layer (Data Mapping)
**Responsibility:** Define relationships, casts, accessors.

**Model Rules:**
- Define relationships only
- Use casts for type safety
- Minimal logic (use traits)
- No heavy computations
- Use scopes for common filters

---

## 3. Service Layer Pattern

```php
namespace App\Services;

use App\Repositories\SeniorCitizenRepository;
use Illuminate\Pagination\Paginator;

class SeniorCitizenService
{
    public function __construct(
        private SeniorCitizenRepository $repository,
        private CacheService $cache
    ) {}

    public function getAllPaginated(int $perPage = 15, array $filters = [])
    {
        // Use cache for filtered results
        $cacheKey = $this->cache->buildKey('seniors', $filters, $perPage);
        
        return Cache::remember($cacheKey, now()->addHour(), function () use ($filters, $perPage) {
            return $this->repository->filtered($filters)->paginate($perPage);
        });
    }

    public function create(array $data)
    {
        $senior = $this->repository->create($data);
        $this->cache->invalidateTag('seniors');
        activity('created_senior', $senior->id);
        return $senior;
    }

    public function update(int $id, array $data)
    {
        $senior = $this->repository->update($id, $data);
        $this->cache->invalidateTag('seniors');
        activity('updated_senior', $senior->id);
        return $senior;
    }
}
```

---

## 4. Repository Pattern

```php
namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    protected function applyFilters($query, array $filters = [])
    {
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, 'LIKE', "%{$value}%");
            }
        }
        return $query;
    }
}

class SeniorCitizenRepository extends BaseRepository
{
    protected Model $model = 'App\Models\SeniorCitizen';

    public function __construct()
    {
        $this->model = app(SeniorCitizen::class);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with(['pensionDistributions', 'familyMembers', 'auditLogs'])
            ->findOrFail($id);
    }

    public function filtered(array $filters = [])
    {
        $query = $this->model->with(['pensionDistributions']);

        if (isset($filters['search'])) {
            $query->where('first_name', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('last_name', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('id_number', '=', $filters['search']);
        }

        if (isset($filters['barangay'])) {
            $query->where('barangay', $filters['barangay']);
        }

        if (isset($filters['status'])) {
            match($filters['status']) {
                'alive' => $query->whereNull('date_of_death'),
                'deceased' => $query->whereNotNull('date_of_death'),
                default => null
            };
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function getBySocialPensionStatus(bool $hasPension = true)
    {
        return $this->model
            ->whereHas('pensionDistributions', function ($q) {
                $q->where('status', 'claimed');
            })
            ->orderBy('last_name')->get();
    }

    public function getDeceased()
    {
        return $this->model
            ->whereNotNull('date_of_death')
            ->orderBy('date_of_death', 'desc')
            ->get();
    }
}
```

---

## 5. Controller Pattern (Refactored)

```php
namespace App\Http\Controllers\SeniorCitizens;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeniorCitizenRequest;
use App\Services\SeniorCitizenService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeniorCitizenController extends Controller
{
    public function __construct(
        private SeniorCitizenService $service
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $seniors = $this->service->getAllPaginated(
            perPage: 20,
            filters: request()->only(['search', 'barangay', 'status'])
        );

        return view('senior-citizens.index', [
            'seniors' => $seniors,
            'filters' => request()->all(),
        ]);
    }

    public function create(): View
    {
        return view('senior-citizens.create', [
            'barangays' => Barangay::all(),
            'remarks' => Remarks::all(),
        ]);
    }

    public function store(SeniorCitizenRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('senior-citizens.index')
                ->with('message', 'Senior citizen record created successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show(int $id): View
    {
        $senior = $this->service->getWithRelations($id);
        return view('senior-citizens.show', compact('senior'));
    }

    public function edit(int $id): View
    {
        $senior = $this->service->getWithRelations($id);
        return view('senior-citizens.edit', compact('senior'));
    }

    public function update(SeniorCitizenRequest $request, int $id): RedirectResponse
    {
        try {
            $this->service->update($id, $request->validated());
            return redirect()->route('senior-citizens.show', $id)
                ->with('message', 'Senior citizen record updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->service->delete($id);
            return redirect()->route('senior-citizens.index')
                ->with('message', 'Senior citizen record deleted successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
```

---

## 6. Caching Strategy

```php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    const TAGS = [
        'seniors' => 'seniors',
        'pension' => 'pension',
        'reports' => 'reports',
        'dashboard' => 'dashboard',
    ];

    const TTL = [
        'realtime' => 5,        // minutes
        'short' => 30,
        'medium' => 60,
        'long' => 1440,         // 24 hours
    ];

    public function buildKey($prefix, array $params = [], $page = 1): string
    {
        $paramString = md5(json_encode($params));
        return "{$prefix}:{$paramString}:{$page}";
    }

    public function remember($key, $ttl, $callback)
    {
        return Cache::remember(
            $key,
            now()->addMinutes($ttl),
            $callback
        );
    }

    public function rememberWithTag($tag, $key, $ttl, $callback)
    {
        return Cache::tags([$tag])->remember(
            $key,
            now()->addMinutes($ttl),
            $callback
        );
    }

    public function invalidateTag(string $tag)
    {
        Cache::tags([$tag])->flush();
    }

    public function getUserActivityFeed(int $limit = 10)
    {
        return $this->rememberWithTag(
            self::TAGS['dashboard'],
            'activity_feed',
            self::TTL['short'],
            fn() => AuditLog::latest()->limit($limit)->get()
        );
    }

    public function getDashboardMetrics()
    {
        return $this->rememberWithTag(
            self::TAGS['dashboard'],
            'metrics',
            self::TTL['short'],
            function () {
                return [
                    'total_seniors' => SeniorCitizen::count(),
                    'social_pension' => SeniorCitizen::whereHas('pensionDistributions')->count(),
                    'deceased' => SeniorCitizen::whereNotNull('date_of_death')->count(),
                    'with_disability' => SeniorCitizen::where('with_disability', true)->count(),
                ];
            }
        );
    }
}
```

---

## 7. Query Optimization Patterns

### Pattern 1: Eager Loading
```php
// ❌ BAD: N+1 queries
$seniors = SeniorCitizen::all();
foreach ($seniors as $senior) {
    echo $senior->pensionDistributions->count();
}

// ✅ GOOD: Single query
$seniors = SeniorCitizen::with('pensionDistributions')->get();
foreach ($seniors as $senior) {
    echo $senior->pensionDistributions->count();
}
```

### Pattern 2: Pagination
```php
// ❌ BAD: Load all records
$seniors = SeniorCitizen::get();
$paginated = $seniors->paginate(15);

// ✅ GOOD: Paginate at database level
$seniors = SeniorCitizen::paginate(15);
```

### Pattern 3: Select Specific Columns
```php
// ❌ BAD: Load all columns
$seniors = SeniorCitizen::all();

// ✅ GOOD: Load only needed columns
$seniors = SeniorCitizen::select('id', 'first_name', 'last_name', 'barangay')->get();
```

### Pattern 4: Use Chunks for Large Datasets
```php
// ❌ BAD: Load 50k records in memory
$seniors = SeniorCitizen::all();

// ✅ GOOD: Process in chunks
SeniorCitizen::chunk(1000, function ($seniors) {
    foreach ($seniors as $senior) {
        ProcessSeniorCitizen::dispatch($senior);
    }
});
```

---

## 8. UI/UX Standards

### SaaS Design Principles
- **No Emojis:** Use Heroicons (24px) or Lucide icons
- **No Heavy Gradients:** Use subtle background colors (bg-slate-50, bg-slate-100)
- **Card-Based Layout:** p-6 standard, rounded-lg, shadow-sm
- **Consistent Spacing:** Use Tailwind spacing scale (4px base)
- **Professional Typography:** Inter font, consistent hierarchy
- **Subtle Shadows:** shadow-sm (default), shadow-md (hover)
- **Color Palette:** Slate-gray primary, blue accent, green success, red danger

### Typography Scale
```
Display: text-4xl font-bold     (44px) - Page titles
Heading XL: text-2xl font-bold  (24px) - Section titles
Heading LG: text-xl font-semibold (20px) - Card titles
Body LG: text-base font-normal  (16px) - Body text
Body SM: text-sm font-normal    (14px) - Secondary text
Label: text-xs font-semibold    (12px) - Form labels
Caption: text-xs font-normal    (12px) - Help text
```

### Color Usage
```
Primary (Navy): text-slate-900, bg-slate-50
Secondary (Gray): text-slate-600, bg-slate-100
Accent (Blue): text-blue-600, bg-blue-50
Success (Green): text-green-600, bg-green-50
Warning (Amber): text-amber-600, bg-amber-50
Danger (Red): text-red-600, bg-red-50
```

---

## 9. Component Architecture

All components follow this pattern:
- PropTypes defined in comments
- Minimal logic
- CSS classes using Tailwind
- Icon slots for customization
- Action slots for buttons

Example components:
- `card`: Container with title, action slot
- `table`: Paginated, sortable, with bulk actions
- `form-field`: Input, validation, help text
- `button`: Variants (primary, secondary, danger), sizes
- `badge`: Status badges with icon support
- `modal`: Overlay modal with close button
- `action-dropdown`: Common CRUD actions

---

## 10. Scalability Roadmap

### Phase 1 (Current): Foundation
- [x] Service layer architecture
- [x] Repository pattern
- [x] Caching strategy
- [x] Clean controllers
- [x] SaaS UI design

### Phase 2 (Next): API & Content
- [ ] REST API endpoints (duplicate of web routes)
- [ ] API authentication (Passport/Sanctum)
- [ ] API documentation (OpenAPI)
- [ ] Bulk operations (imports, exports)

### Phase 3 (Future): SPA Ready
- [ ] Inertia.js integration
- [ ] Vue 3 components
- [ ] Real-time updates (Livewire or Pusher)
- [ ] Client-side routing

### Phase 4 (Future): Enterprise
- [ ] Multi-branch support (tenant isolation)
- [ ] Advanced reporting
- [ ] Webhooks for integrations
- [ ] Role-based access control (RBAC)

---

## 11. Testing Strategy

```php
// Unit Tests: Business logic
Tests/Unit/Services/SeniorCitizenServiceTest.php
- Test service methods in isolation
- Mock repositories
- Verify calculations

// Feature Tests: User flows
Tests/Feature/SeniorCitizens/CreateSeniorCitizenTest.php
- Test full flow (form -> controller -> service -> DB)
- Verify redirects
- Check cache invalidation

// API Tests
Tests/Feature/Api/SeniorCitizensApiTest.php
- Test API endpoints
- Verify response formats
- Check pagination
```

---

## 12. Performance Benchmarks (Targets)

- Dashboard load: < 200ms
- Senior Citizens list (20 records): < 300ms
- Search (1000 records): < 500ms
- Bulk import (5000 records): < 10s
- Report generation: < 5s
- Peak capacity: 10k concurrent users

---

## 13. Security Considerations

- **CSRF Protection:** Use @csrf on all forms
- **Authorization:** Gate/Policy resources (senior-citizens can only view their own)
- **Validation:** Form Request classes validate all input
- **SQL Injection:** Use parameter binding (where('column', $value))
- **XSS Prevention:** Escape output with {{ }} in Blade
- **Rate Limiting:** API endpoints rate-limited
- **Activity Logging:** All CRUD operations logged

---

## 14. Deployment Checklist

Before production:

```markdown
- [ ] Run tests (100% coverage target)
- [ ] Cache tables are indexed
- [ ] Eager loading in all queries
- [ ] Caching configured (Redis recommended)
- [ ] Logging configured
- [ ] Error tracking (Sentry) configured
- [ ] CDN configured for static assets
- [ ] Database backups enabled
- [ ] Monitoring/alerting configured
- [ ] Documentation updated
- [ ] Team trained on architecture
```

---

## 15. Key Takeaways

✅ **Controllers:** Thin, no business logic
✅ **Services:** Fat, all business logic
✅ **Repositories:** Data access abstraction
✅ **Caching:** Strategic, tag-based invalidation
✅ **Queries:** Eager load, paginate, select specific columns
✅ **Design:** Professional SaaS aesthetic
✅ **Scalability:** Ready for multi-tenant, API, SPA
✅ **Testing:** Unit + Feature coverage
✅ **Performance:** Optimized for 50k+ records

This architecture supports scaling to millions of records while maintaining clean, maintainable code.

# System Architecture Visualization

## Overall Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                         HTTP REQUEST                                 │
│                                                                       │
│  route() → middleware → Controller → Service → Repository → Model   │
│                            ↓                                          │
│                      (authentication)                                 │
└──────────────────────────────┬──────────────────────────────────────┘
                               │
                    ┌──────────┴──────────┐
                    ↓                     ↓
            ┌──────────────┐      ┌──────────────┐
            │   Validate   │      │ Check Cache  │
            │    Input     │      │   (Redis)    │
            └──────┬───────┘      └──────┬───────┘
                   ↓                     ↓
            ┌──────────────┐      ┌──────────────┐
            │  Database    │      │  Return      │
            │   Query      │      │   Cached     │
            └──────┬───────┘      └──────────────┘
                   ↓
            ┌──────────────┐
            │ Call Service │
            │   Methods    │
            └──────┬───────┘
                   ├─────────── Tag Cache
                   ├─────────── Log Activity
                   └─────────── Return Data
                               ↓
                    ┌──────────────────────┐
                    │ Format for Response  │
                    │ (array/JSON/View)    │
                    └──────┬───────────────┘
                           ↓
                    ┌──────────────────────┐
                    │  HTTP RESPONSE       │
                    │  (View/JSON)         │
                    └──────────────────────┘
```

---

## Layered Architecture

```
                        ┌─────────────────────..................┐
                        │   PRESENTATION LAYER                  │
                        │  (Blade Templates)                    │
                        ├─────────────────────..................┤
                        │   Components:                         │
                        │  - x-card                             │
                        │  - x-saas-button                      │
                        │  - x-kpi-card                         │
                        │  - x-saas-table                       │
                        │  - x-action-dropdown                  │
                        └─────────────────┬──..................┘
                                          │
                        ┌─────────────────▼──..................┐
                        │   HTTP LAYER                         │
                        │  (Controllers)                       │
                        ├─────────────────────..................┤
                        │  Responsibilities:                   │
                        │  - Route handling                    │
                        │  - Input validation (FormRequest)    │
                        │  - Delegation to services            │
                        │  - Response formatting               │
                        │                                      │
                        │  DashboardController                 │
                        │  SeniorCitizenController             │
                        │  ReportController                    │
                        └─────────────────┬──..................┘
                                          │
                        ┌─────────────────▼──..................┐
                        │   BUSINESS LOGIC LAYER               │
                        │  (Services)                          │
                        ├─────────────────────..................┤
                        │                                      │
                        │  ┌─────────────────────────────┐     │
                        │  │  DashboardService           │     │
                        │  │  - Aggregations             │     │
                        │  │  - Calculations             │     │
                        │  │  - Data formatting          │     │
                        │  └─────────────────────────────┘     │
                        │                                      │
                        │  ┌─────────────────────────────┐     │
                        │  │  SeniorCitizenService       │     │
                        │  │  - CRUD operations          │     │
                        │  │  - Advanced filtering       │     │
                        │  │  - Bulk operations          │     │
                        │  │  - Activity logging         │     │
                        │  └─────────────────────────────┘     │
                        │                                      │
                        │  ┌─────────────────────────────┐     │
                        │  │  ReportService              │     │
                        │  │  - Report generation        │     │
                        │  │  - Data aggregation         │     │
                        │  │  - Export formatting        │     │
                        │  └─────────────────────────────┘     │
                        │                                      │
                        │  ┌─────────────────────────────┐     │
                        │  │  CacheService               │     │
                        │  │  - Cache management         │     │
                        │  │  - Tag-based invalidation   │     │
                        │  │  - TTL configuration        │     │
                        │  └─────────────────────────────┘     │
                        │                                      │
                        └─────────────────┬──..................┘
                                          │
                        ┌─────────────────▼──..................┐
                        │   DATA ACCESS LAYER                  │
                        │  (Repositories - Optional)          │
                        ├─────────────────────..................┤
                        │  Responsibilities:                   │
                        │  - Query abstraction                 │
                        │  - Eager loading                     │
                        │  - Filter application                │
                        │  - Pagination                        │
                        │                                      │
                        │  SeniorCitizenRepository             │
                        │  PensionDistributionRepository       │
                        │  (Future additions)                  │
                        └─────────────────┬──..................┘
                                          │
                        ┌─────────────────▼──..................┐
                        │   MODEL LAYER                        │
                        │  (Eloquent Models)                   │
                        ├─────────────────────..................┤
                        │  - SeniorCitizen                     │
                        │  - PensionDistribution               │
                        │  - AuditLog                          │
                        │  - User                              │
                        │  - FamilyMember                      │
                        └─────────────────┬──..................┘
                                          │
                        ┌─────────────────▼──..................┐
                        │   DATABASE LAYER                     │
                        │  (MySQL)                             │
                        ├─────────────────────..................┤
                        │  - Tables                            │
                        │  - Indexes                           │
                        │  - Relationships                     │
                        └────────────────────..................┘

                        ┌─────────────────────..................┐
                        │   CACHING LAYER (Redis)              │
                        │  (Distributed Cache)                │
                        ├─────────────────────..................┤
                        │  - dashboard.metrics                 │
                        │  - seniors:paginated                 │
                        │  - reports.*                         │
                        │  - analytics.*                       │
                        └────────────────────..................┘
```

---

## Component Architecture

```
                  ┌───────────────────────────────────┐
                  │     Reusable Components           │
                  ├───────────────────────────────────┤
                  │                                   │
        ┌─────────┼─────────┬──────────┬────────────┐ │
        │         │         │          │            │ │
    ┌───▼──┐  ┌───▼──┐  ┌──▼────┐  ┌─▼────┐  ┌──▼────┐
    │Card  │  │Button│  │Table  │  │Badge │  │Modal  │
    └──────┘  └──────┘  └───────┘  └──────┘  └───────┘
        │         │         │          │            │
        │    ┌────┴─┬───────┴──┬───────┴────────────┤
        │    │ Composition continues...             │
        │    │                                      │
        │    └──────────────────────────────────────┘
        │
    ┌───▼─────────────────────────────────────────┐
    │  Used in all Views                          │
    │                                             │
    │ - dashboard.blade.php                       │
    │ - senior-citizens/index.blade.php           │
    │ - senior-citizens/create.blade.php          │
    │ - senior-citizens/edit.blade.php            │
    │ - reports/index.blade.php                   │
    │ - history/index.blade.php                   │
    │ - users/index.blade.php                     │
    │                                             │
    └─────────────────────────────────────────────┘
```

---

## Data Flow - Senior Citizens CRUD

```
┌──────────────────────────────────────────────────────────────────┐
│                   USER CREATES NEW SENIOR                        │
└──────────────────────────────────────────────────────────────────┘
                              ↓
                    ┌─────────────────────┐
                    │  Form Submission    │
                    │  POST /seniors      │
                    └────────────┬────────┘
                                 ↓
                    ┌─────────────────────────────────┐
                    │ Validate: SeniorCitizenRequest  │
                    │ - first_name required           │
                    │ - email unique                  │
                    │ - age >= 60                     │
                    └────────────┬────────────────────┘
                                 ↓
            ┌────────────────────────────────────────┐
            │  SeniorCitizenController::store()       │
            │                                        │
            │  1. Check authorization (Policy)       │
            │  2. Inject SeniorCitizenService        │
            │  3. Call: service->create($data)       │
            │  4. Return redirect with message       │
            └────────────┬───────────────────────────┘
                         ↓
            ┌─────────────────────────────────────────┐
            │  SeniorCitizenService::create()          │
            │                                         │
            │  1. Create senior in database           │
            │  2. Log activity to audit log           │
            │  3. Invalidate 'seniors' cache          │
            │  4. Return senior object                │
            │  5. Log success message                 │
            └────────────┬────────────────────────────┘
                         ↓
            ┌─────────────────────────────────────────┐
            │  CacheService::invalidateTag('seniors') │
            │                                         │
            │  Flushes all keys tagged:               │
            │  - Cache:seniors:...                    │
            │  - Analysis becomes fresh               │
            └────────────┬────────────────────────────┘
                         ↓
                  ┌────────────────┐
                  │ Return View    │
                  │ Redirect       │
                  │ to index       │
                  └────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│              USER VIEWS LIST (WITH CACHE HIT)                    │
└──────────────────────────────────────────────────────────────────┘
                              ↓
                    ┌─────────────────────┐
                    │  GET /seniors       │
                    │  ?search=&page=1    │
                    └────────────┬────────┘
                                 ↓
                    ┌──────────────────────────────┐
                    │ SeniorCitizenController       │
                    │ index()                      │
                    │                              │
                    │ service->getAllPaginated(    │
                    │   20,                        │
                    │   filters: request()->only() │
                    │ )                            │
                    └────────────┬─────────────────┘
                                 ↓
        ┌────────────────────────────────────────────────┐
        │  Check Cache Key:                              │
        │  "seniors:filtered:{md5(filters)}:{page}"      │
        │                                                │
        │  HIT? → Return cached collection               │
        │  MISS? → Continue to DB query                  │
        └────────────┬───────────────────────────────────┘
                     ↓
        ┌────────────────────────────────────────────────┐
        │  SeniorCitizenRepository::filtered()            │
        │                                                 │
        │  SELECT * FROM senior_citizens                 │
        │  WHERE barangay = ? AND deleted_at IS NULL     │
        │  (with indexes)                                │
        │  EAGER LOAD: pensionDistributions              │
        │  PAGINATE: 20 records                          │
        │                                                 │
        │  Result: LengthAwarePaginator object            │
        └────────────┬───────────────────────────────────┘
                     ↓
        ┌────────────────────────────────────────────────┐
        │  Store in Cache (60 minutes)                    │
        │  Tag: 'seniors'                                │
        │  Result cached for future requests             │
        └────────────┬───────────────────────────────────┘
                     ↓
                ┌──────────────┐
                │ Return View  │
                │ Pass $seniors│
                │ to Blade     │
                └──────────────┘
                     ↓
        ┌────────────────────────────────────────────────┐
        │  Render Blade Template                         │
        │  Uses x-saas-table component                   │
        │  Displays all fields                           │
        │  Shows action dropdown for each row            │
        │  Renders pagination links                      │
        └────────────┬───────────────────────────────────┘
                     ↓
                   HTML
                    ↓
              Browser Renders
```

---

## Service Interaction Pattern

```
┌─────────────────────────────────────────────────────┐
│              Controller receives request             │
└─────────────────────────────┬───────────────────────┘
                              │
                              ▼
            ┌─────────────────────────────────┐
            │ new SeniorCitizenController      │
            │ (DI: SeniorCitizenService)       │
            └─────────────┬───────────────────┘
                          │
                          ▼
            ┌─────────────────────────────────┐
            │ Validate with FormRequest       │
            │ (authentication checks)         │
            └─────────────┬───────────────────┘
                          │
                          ▼
            ┌─────────────────────────────────────────────┐
            │ Call Service Method:                        │
            │ - create($data)                             │
            │ - update($id, $data)                        │
            │ - delete($id)                               │
            │ - getAllPaginated($per, $filters)           │
            │ - getById($id)                              │
            └─────────────┬───────────────────────────────┘
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
    ┌──────────┐   ┌──────────────┐   ┌─────────────┐
    │ Validate │   │ Query/Modify │   │ Log Changes │
    │  Rules   │   │  Database    │   │ (AuditLog)  │
    └──────────┘   └──────────────┘   └─────────────┘
        │                 │                 │
        └─────────────────┼─────────────────┘
                          │
                          ▼
        ┌──────────────────────────────────┐
        │ Invalidate Relevant Cache Tags   │
        │ - Cache::tags(['seniors']),      │
        │ - Analytics dependent on data    │
        └──────────┬───────────────────────┘
                   │
                   ▼
        ┌──────────────────────────────────┐
        │ Return Data/Result               │
        │ (Model / Collection / Count)     │
        └──────────┬───────────────────────┘
                   │
                   ▼
        ┌──────────────────────────────────┐
        │ Controller Formats Response      │
        │ - Redirect with message          │
        │ - View with data                 │
        │ - JSON response                  │
        └──────────────────────────────────┘
```

---

## Caching Strategy

```
┌─────────────────────────────────────┐
│  Cache Request Lifecycle            │
├─────────────────────────────────────┤
│                                     │
│  1. Build Cache Key                 │
│     "seniors:md5(filters):page"     │
│                                     │
│  2. Check if key exists (Redis)     │
│     Hit? → Return immediately       │
│     Miss? → Continue                │
│                                     │
│  3. Execute query                   │
│     - DB query executed             │
│     - Related data eager-loaded     │
│     - Paginated results             │
│                                     │
│  4. Store in cache                  │
│     - Key: "seniors:..."            │
│     - Value: Collection             │
│     - Tag: ['seniors']              │
│     - TTL: 30 minutes               │
│                                     │
│  5. Return to caller                │
│                                     │
│  6. Invalidation trigger (at change)│
│     Cache::tags(['seniors'])        │
│     ->flush();                      │
│                                     │
│  7. Next request gets fresh         │
│     (cache miss again)              │
│                                     │
└─────────────────────────────────────┘

Backend: Redis
Tags: ['seniors', 'dashboard', 'reports', 'analytics']
TTL: 5min (realtime), 30min (short), 60min (medium), 1440 (long)
```

---

## Query Optimization Patterns

```
❌ BAD PATTERN (N+1 Query)
────────────────────────────────────
$seniors = SeniorCitizen::all();  // 1 query: 1000 records

foreach ($seniors as $senior) {
    echo $senior->pensionDistributions;  // 1000 queries!
}
// Total: 1001 queries ❌


✅ GOOD PATTERN (Eager Loading)
────────────────────────────────────
$seniors = SeniorCitizen::with('pensionDistributions')
    ->paginate(20);  // 2 queries total: 1 for seniors, 1 for all pension records

foreach ($seniors as $senior) {
    echo $senior->pensionDistributions;  // Already loaded
}
// Total: 2 queries ✅


✅ SELECT COLUMNS PATTERN
────────────────────────────────────
$seniors = SeniorCitizen::select('id', 'first_name', 'last_name')
    ->paginate(20);  // Smaller payload


✅ CHUNKING PATTERN (Large Exports)
────────────────────────────────────
SeniorCitizen::chunk(1000, function ($seniors) {
    foreach ($seniors as $senior) {
        ProcessSenior::dispatch($senior);  // Queue processing
    }
});
// Processes in batches, prevents memory overflow
```

---

This architecture is designed to scale with your growing needs while maintaining clean code and optimal performance.


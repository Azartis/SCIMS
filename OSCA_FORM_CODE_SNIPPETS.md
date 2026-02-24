# OSCA Form - Code Snippets & Usage Examples

## 1. Model Usage Examples

### Creating a New Senior Citizen with Family Members

```php
use App\Models\SeniorCitizen;
use App\Models\FamilyMember;

// Create senior citizen
$senior = new SeniorCitizen([
    'osca_id' => 'OSCA-2026-001',
    'firstname' => 'Juan',
    'middlename' => 'Dela',
    'lastname' => 'Cruz',
    'date_of_birth' => '1950-02-20',
    'sex' => 'Male',
    'civil_status' => 'Married',
    'address' => '123 Main St.',
    'barangay' => 'Barangay1',
    'contact_number' => '09XX-XXX-XXXX',
    'is_pensioner' => true,
    'pension_type' => 'SSS',
    'monthly_pension_amount' => 5000,
    'total_monthly_income' => 8000,
]);

// Auto-calculate age
$senior->calculateAge();  // Sets age=74, age_range='70-79'

$senior->save();

// Add family members
FamilyMember::create([
    'senior_citizen_id' => $senior->id,
    'name' => 'Maria Cruz',
    'relationship' => 'Spouse',
    'age' => 72,
    'civil_status' => 'Married',
    'occupation' => 'Homemaker',
    'monthly_income' => 3000,
    'address' => '123 Main St.',
]);

FamilyMember::create([
    'senior_citizen_id' => $senior->id,
    'name' => 'Pedro Cruz Jr.',
    'relationship' => 'Son',
    'age' => 45,
    'civil_status' => 'Married',
    'occupation' => 'Engineer',
    'monthly_income' => 25000,
    'address' => '456 Oak Ave.',
]);
```

### Retrieving and Displaying Data

```php
// Get single senior citizen with family
$senior = SeniorCitizen::with('familyMembers')->find(1);

echo "Senior Citizen: " . $senior->getFormattedDisplayName();
echo "Age: " . $senior->age;
echo "Age Range: " . $senior->age_range;
echo "Total Family Income: ₱" . number_format($senior->getTotalFamilyIncome(), 2);

// List family members
foreach ($senior->familyMembers as $member) {
    echo "- {$member->name} ({$member->relationship}): ₱{$member->monthly_income}/month";
}

// Check health conditions
if ($senior->with_disability) {
    echo "Has {$senior->type_of_disability} disability";
}

// Check income classification
if ($senior->is_pensioner) {
    echo "{$senior->pension_type} pensioner since ₱{$senior->monthly_pension_amount}/month";
}
if ($senior->is_indigent) {
    echo "Classified as indigent";
}
```

---

## 2. Controller & Filtering Examples

### Advanced Filtering Query

```php
// Filter pensioners aged 70-79 in specific barangay with disability
$seniors = SeniorCitizen::withoutTrashed()
    ->where('is_pensioner', true)
    ->where('age_range', '70-79')
    ->where('barangay', 'Barangay1')
    ->where('with_disability', true)
    ->with('familyMembers')
    ->paginate(10);
```

### Building Dynamic Queries

```php
public function advancedSearch(Request $request)
{
    $query = SeniorCitizen::withoutTrashed();

    // Search text
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('firstname', 'like', "%{$search}%")
              ->orWhere('lastname', 'like', "%{$search}%")
              ->orWhere('osca_id', 'like', "%{$search}%");
        });
    }

    // Classification filters
    $classificationMap = [
        'pensioners' => ['is_pensioner', true],
        'indigent' => ['is_indigent', true],
        'disabled' => ['with_disability', true],
        'bedridden' => ['bedridden', true],
        'critical_illness' => ['with_critical_illness', true],
    ];

    if ($request->filled('classification') && 
        isset($classificationMap[$request->classification])) {
        $filter = $classificationMap[$request->classification];
        $query->where($filter[0], $filter[1]);
    }

    // Age range
    if ($request->filled('age_range')) {
        $query->where('age_range', $request->age_range);
    }

    // Demographics
    if ($request->filled('sex')) {
        $query->where('sex', $request->sex);
    }

    if ($request->filled('barangay')) {
        $query->where('barangay', $request->barangay);
    }

    // Pagination
    return $query->with('familyMembers')
        ->paginate(15)
        ->appends($request->query());
}
```

---

## 3. Blade Template Examples

### Display Senior Citizen Card

```blade
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <h3 class="text-lg font-bold">{{ $senior->getFormattedDisplayName() }}</h3>
    
    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="font-semibold">OSCA ID:</span>
            <span>{{ $senior->osca_id }}</span>
        </div>
        <div>
            <span class="font-semibold">Age:</span>
            <span>{{ $senior->age }} years ({{ $senior->age_range }})</span>
        </div>
        <div>
            <span class="font-semibold">Sex:</span>
            <span>{{ $senior->sex }}</span>
        </div>
        <div>
            <span class="font-semibold">Civil Status:</span>
            <span>{{ $senior->civil_status ?? 'N/A' }}</span>
        </div>
        <div class="col-span-2">
            <span class="font-semibold">Address:</span>
            <span>{{ $senior->address }}, {{ $senior->barangay }}</span>
        </div>
    </div>

    <!-- Classification Badges -->
    <div class="mt-4 flex flex-wrap gap-2">
        @if ($senior->is_pensioner)
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                Pensioner ({{ $senior->pension_type }})
            </span>
        @endif

        @if ($senior->is_indigent)
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                Indigent
            </span>
        @endif

        @if ($senior->with_disability)
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                With Disability
            </span>
        @endif

        @if ($senior->bedridden)
            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                Bedridden
            </span>
        @endif

        @if ($senior->with_critical_illness)
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                Critical Illness
            </span>
        @endif
    </div>
</div>
```

### Display Family Members Table

```blade
<div class="mt-6">
    <h4 class="text-lg font-bold mb-4">Family Composition</h4>
    
    @if ($senior->familyMembers->count() > 0)
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Relationship</th>
                    <th class="px-4 py-2 text-center">Age</th>
                    <th class="px-4 py-2 text-left">Occupation</th>
                    <th class="px-4 py-2 text-right">Monthly Income</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($senior->familyMembers as $member)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $member->name }}</td>
                        <td class="px-4 py-2">{{ $member->relationship }}</td>
                        <td class="px-4 py-2 text-center">{{ $member->age }}</td>
                        <td class="px-4 py-2">{{ $member->occupation ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">
                            ₱{{ number_format($member->monthly_income ?? 0, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-gray-50 dark:bg-gray-900">
                    <td colspan="4" class="px-4 py-2 text-right">Total Family Income:</td>
                    <td class="px-4 py-2 text-right">
                        ₱{{ number_format($senior->getTotalFamilyIncome(), 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <p class="text-gray-500">No family members recorded.</p>
    @endif
</div>
```

### Filter Form

```blade
<form method="GET" action="{{ route('senior-citizens.index') }}" class="bg-white p-4 rounded-lg mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-semibold mb-1">Search</label>
            <input type="text" name="search" 
                   value="{{ request('search') }}"
                   placeholder="Name, OSCA ID..."
                   class="w-full px-3 py-2 border rounded-lg">
        </div>

        <!-- Classification -->
        <div>
            <label class="block text-sm font-semibold mb-1">Classification</label>
            <select name="classification" class="w-full px-3 py-2 border rounded-lg">
                <option value="">All Types</option>
                <option value="pensioners" @selected(request('classification') === 'pensioners')>
                    Pensioners
                </option>
                <option value="indigent" @selected(request('classification') === 'indigent')>
                    Indigent
                </option>
                <option value="with_disability" @selected(request('classification') === 'with_disability')>
                    With Disability
                </option>
                <option value="bedridden" @selected(request('classification') === 'bedridden')>
                    Bedridden
                </option>
                <option value="critical_illness" @selected(request('classification') === 'critical_illness')>
                    Critical Illness
                </option>
            </select>
        </div>

        <!-- Age Range -->
        <div>
            <label class="block text-sm font-semibold mb-1">Age Range</label>
            <select name="age_range" class="w-full px-3 py-2 border rounded-lg">
                <option value="">All Ages</option>
                <option value="60-69" @selected(request('age_range') === '60-69')>60-69</option>
                <option value="70-79" @selected(request('age_range') === '70-79')>70-79</option>
                <option value="80+" @selected(request('age_range') === '80+')>80+</option>
            </select>
        </div>

        <!-- Barangay -->
        <div>
            <label class="block text-sm font-semibold mb-1">Barangay</label>
            <select name="barangay" class="w-full px-3 py-2 border rounded-lg">
                <option value="">All Barangays</option>
                @foreach (\App\Constants\Barangay::list() as $brgy)
                    <option value="{{ $brgy }}" @selected(request('barangay') === $brgy)>
                        {{ $brgy }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Filter
        </button>
        <a href="{{ route('senior-citizens.index') }}" 
           class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
            Clear
        </a>
    </div>
</form>
```

---

## 4. Artisan Command Examples

### Create Test Senior Citizens

```php
// In Command or Seed file
use App\Models\SeniorCitizen;

for ($i = 0; $i < 10; $i++) {
    $senior = SeniorCitizen::create([
        'osca_id' => 'TEST-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
        'firstname' => fake()->firstName(),
        'lastname' => fake()->lastName(),
        'date_of_birth' => fake()->dateTimeBetween('1930-01-01', '1964-01-01'),
        'sex' => fake()->randomElement(['Male', 'Female']),
        'civil_status' => fake()->randomElement(['Single', 'Married', 'Widowed']),
        'address' => fake()->address(),
        'barangay' => fake()->randomElement(\App\Constants\Barangay::list()),
        'contact_number' => fake()->phoneNumber(),
        'is_pensioner' => fake()->boolean(70),
        'pension_type' => fake()->randomElement(['SSS', 'GSIS', 'PVAO']),
        'monthly_pension_amount' => fake()->numberBetween(5000, 20000),
        'total_monthly_income' => fake()->numberBetween(5000, 30000),
        'is_indigent' => fake()->boolean(30),
        'with_disability' => fake()->boolean(20),
        'bedridden' => fake()->boolean(5),
    ]);
    
    $senior->calculateAge();
    $senior->save();
}
```

---

## 5. Reporting Query Examples

### Get Statistics Dashboard

```php
// Statistics for dashboard
$stats = [
    'total' => SeniorCitizen::count(),
    'pensioners' => SeniorCitizen::where('is_pensioner', true)->count(),
    'indigent' => SeniorCitizen::where('is_indigent', true)->count(),
    'with_disability' => SeniorCitizen::where('with_disability', true)->count(),
    'bedridden' => SeniorCitizen::where('bedridden', true)->count(),
    'critical_illness' => SeniorCitizen::where('with_critical_illness', true)->count(),
    'avg_age' => SeniorCitizen::avg('age'),
    'age_60_69' => SeniorCitizen::where('age_range', '60-69')->count(),
    'age_70_79' => SeniorCitizen::where('age_range', '70-79')->count(),
    'age_80plus' => SeniorCitizen::where('age_range', '80+')->count(),
    'avg_family_income' => SeniorCitizen::avg('total_monthly_income'),
    'avg_family_size' => SeniorCitizen::withCount('familyMembers')
        ->get()
        ->average('family_members_count'),
];

return response()->json($stats);
```

### Export to CSV/Excel

```php
use Maatwebsite\Excel\Facades\Excel;

public function export(Request $request)
{
    $query = SeniorCitizen::query();

    // Apply any filters
    if ($request->filled('classification')) {
        // Apply classification filter
    }

    $seniors = $query->with('familyMembers')->get();

    return Excel::download(
        new SeniorCitizensExport($seniors),
        'senior-citizens-' . now()->format('Y-m-d') . '.xlsx'
    );
}
```

---

## 6. Validation Examples

### Custom Validation Rule

```php
// In Form Request
public function rules()
{
    return [
        'osca_id' => ['required', 'string', 'unique:senior_citizens,osca_id,' . $this->id],
        'date_of_birth' => ['required', 'date', 'before:today', function($attribute, $value, $fail) {
            $age = now()->diffInYears($value);
            if ($age < 60) {
                $fail('Senior citizen must be at least 60 years old.');
            }
        }],
        'total_monthly_income' => ['required', 'numeric', 'min:0'],
        'family_members.*.monthly_income' => ['nullable', 'numeric', 'min:0'],
    ];
}

public function messages()
{
    return [
        'osca_id.unique' => 'OSCA ID already exists in the system.',
        'date_of_birth.before' => 'Date of birth must be in the past.',
        'family_members.*.monthly_income.numeric' => 'Family member monthly income must be a valid number.',
    ];
}
```

---

## 7. Event/Observer Examples

### Auto-Calculate Age on Create/Update

```php
namespace App\Observers;

use App\Models\SeniorCitizen;

class SeniorCitizenObserver
{
    public function saving(SeniorCitizen $senior)
    {
        // Auto-calculate age
        if ($senior->date_of_birth) {
            $senior->calculateAge();
        }

        // Generate full name if not set
        if (!$senior->fullname || $senior->isDirty(['firstname', 'lastname', 'middlename'])) {
            $fullname = $senior->lastname;
            if ($senior->firstname) $fullname .= ', ' . $senior->firstname;
            if ($senior->middlename) $fullname .= ' ' . $senior->middlename;
            $senior->fullname = trim($fullname);
        }
    }

    public function created(SeniorCitizen $senior)
    {
        // Log audit trail
        activity()
            ->performedOn($senior)
            ->withProperties($senior->toArray())
            ->log('Senior citizen created');
    }

    public function updated(SeniorCitizen $senior)
    {
        // Log changes
        activity()
            ->performedOn($senior)
            ->withProperties($senior->getChanges())
            ->log('Senior citizen updated');
    }
}

// Register in AppServiceProvider
SeniorCitizen::observe(SeniorCitizenObserver::class);
```

---

**Code Snippets Version:** 1.0  
**Last Updated:** February 24, 2026

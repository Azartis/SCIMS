# SCIMS OSCA Form - API & Controller Reference

## SeniorCitizenController Methods

### 1. `index($request)` - List Senior Citizens with Filtering

**URL:** `/senior-citizens`  
**Method:** GET  

**Query Parameters:**

```
?search=string              # Search by name, OSCA ID, contact, address
?sex=Male|Female|Other      # Filter by sex
?barangay=BarangayName      # Filter by barangay
?classification=TYPE        # Filter by classification
?age_range=RANGE            # Filter by age range
?pension_type=TYPE          # Legacy filter
?status=STATUS              # Legacy filter
```

**Classification Filter Options:**
- `pensioners` - is_pensioner = true
- `indigent` - is_indigent = true
- `with_disability` - with_disability = true
- `bedridden` - bedridden = true
- `critical_illness` - with_critical_illness = true

**Age Range Options:**
- `60-69` - Ages 60 to 69
- `70-79` - Ages 70 to 79
- `80+` - Ages 80 and above

**Examples:**

```
/senior-citizens?search=maria
/senior-citizens?classification=pensioners
/senior-citizens?age_range=70-79&classification=with_disability
/senior-citizens?sex=Female&barangay=Barangay1&age_range=60-69
```

**Return:** Paginated list (10 per page) with query parameters preserved

---

### 2. `create()` - Show Create Form

**URL:** `/senior-citizens/create`  
**Method:** GET  

**Return:** Blade template with OSCA intake form

---

### 3. `store($request)` - Save New Senior Citizen

**URL:** `/senior-citizens`  
**Method:** POST  

**Validation Rules:**

```php
// Personal Information
'osca_id'                   => 'required|string|unique',
'firstname'                 => 'required|string|max:255',
'lastname'                  => 'required|string|max:255',
'middlename'                => 'nullable|string|max:255',
'extension_name'            => 'nullable|string|max:50',
'date_of_birth'             => 'required|date|before:today',
'place_of_birth'            => 'nullable|string|max:255',
'sex'                       => 'required|in:Male,Female,Other',
'civil_status'              => 'nullable|in:Single,Married,Widowed,Divorced,Separated,Other',
'citizenship'               => 'nullable|string|max:50',
'religion'                  => 'nullable|string|max:100',
'educational_attainment'    => 'nullable|in:No Formal Education,Elementary,High School,Vocational,College,Post-Graduate',
'address'                   => 'required|string',
'barangay'                  => 'required|in:' . $barangayList,
'contact_number'            => 'nullable|string|max:20',

// Health Condition
'with_disability'           => 'boolean',
'type_of_disability'        => 'nullable|string|max:255',
'bedridden'                 => 'boolean',
'with_assistive_device'     => 'boolean',
'type_of_assistive_device'  => 'nullable|string|max:255',
'with_critical_illness'     => 'boolean',
'specify_illness'           => 'nullable|string',
'philhealth_member'         => 'boolean',
'philhealth_id'             => 'nullable|string|max:50',

// Source of Income
'is_pensioner'              => 'boolean',
'pension_type'              => 'nullable|in:SSS,GSIS,PVAO,Private,Others',
'monthly_pension_amount'    => 'nullable|numeric|min:0',
'other_income_source'       => 'nullable|string',
'total_monthly_income'      => 'nullable|numeric|min:0',

// Classification
'is_indigent'               => 'boolean',

// Family Members (Array)
'family_members.*. name'            => 'nullable|string|max:255',
'family_members.*.relationship'     => 'nullable|string|max:100',
'family_members.*.age'              => 'nullable|integer|min:0',
'family_members.*.civil_status'     => 'nullable|string|max:50',
'family_members.*.occupation'       => 'nullable|string|max:255',
'family_members.*.monthly_income'   => 'nullable|numeric|min:0',
'family_members.*.address'          => 'nullable|string',
```

**Auto-Computed Fields:**
- `age` - Calculated from date_of_birth
- `age_range` - Auto-classified (60-69, 70-79, 80+)
- `fullname` - Generated from name parts

**Function Logic:**
1. Validate all input
2. Create new SeniorCitizen
3. Calculate age and age_range
4. Generate full name
5. Create family members if provided
6. Save to database

**Return:** Redirect to index with success message

---

### 4. `show($id)` - Display Senior Citizen Details

**URL:** `/senior-citizens/{id}`  
**Method:** GET  

**Return:** Blade template with all details and family members

---

### 5. `edit($id)` - Show Edit Form

**URL:** `/senior-citizens/{id}/edit`  
**Method:** GET  

**Return:** Blade template with prefilled form

---

### 6. `update($request, $id)` - Save Updates

**URL:** `/senior-citizens/{id}`  
**Method:** PUT/PATCH  

**Validation Rules:** Same as `store()` but OSCA ID unique constraint allows current record

**Additional Logic:**
1. Validate input
2. Update SeniorCitizen fields
3. Delete old family members
4. Create new family members
5. Recalculate age and age_range

**Return:** Redirect to show with success message

---

### 7. `destroy($id)` - Soft Delete Senior Citizen

**URL:** `/senior-citizens/{id}`  
**Method:** DELETE  

**Logic:**
1. Soft delete senior citizen (archived)
2. Family members cascade deleted via FK

**Return:** Redirect to index with success message

---

### 8. `archive($request)` - List Archived Senior Citizens

**URL:** `/senior-citizens/archive`  
**Method:** GET  

**Query Parameters:**
```
?search=string   # Search by name, OSCA ID
```

**Return:** Paginated list of archived records

---

### 9. `restore($id)` - Restore Archived Senior Citizen

**URL:** `/senior-citizens/{id}/restore`  
**Method:** POST/PUT  

**Logic:**
1. Restore soft-deleted senior citizen
2. Family members also restored via FK

**Return:** Redirect to archive with success message

---

## SeniorCitizen Model Methods

### Properties (Fillable)

```php
[
    // Personal Information
    'firstname', 'middlename', 'lastname', 'extension_name',
    'fullname', 'date_of_birth', 'place_of_birth', 'age', 'sex',
    'civil_status', 'citizenship', 'religion', 'educational_attainment',
    'address', 'barangay', 'contact_number', 'osca_id',
    
    // Health Condition
    'with_disability', 'type_of_disability', 'bedridden',
    'with_assistive_device', 'type_of_assistive_device',
    'with_critical_illness', 'specify_illness',
    'philhealth_member', 'philhealth_id',
    
    // Source of Income
    'is_pensioner', 'pension_type', 'monthly_pension_amount',
    'other_income_source', 'total_monthly_income',
    
    // Classification
    'is_indigent', 'age_range',
    
    // Legacy
    'sss', 'gsis', 'pvao', 'family_pension', 'brgy_official',
    'waitlist', 'social_pension', 'remarks'
]
```

### Relationships

**Get Family Members:**
```php
$senior = SeniorCitizen::find($id);
$familyMembers = $senior->familyMembers;

// or
$familyMembers = $senior->familyMembers()->get();
```

**Get Total Family Income:**
```php
$totalIncome = $senior->getTotalFamilyIncome();
// Returns: senior's income + sum of all family members' income
```

### Methods

**Calculate Age:**
```php
$senior->calculateAge();
$senior->save();
```

Sets:
- `$senior->age` - Computed age
- `$senior->age_range` - '60-69', '70-79', or '80+'

---

## FamilyMember Model

### Properties (Fillable)

```php
[
    'senior_citizen_id',
    'name',
    'relationship',
    'age',
    'civil_status',
    'occupation',
    'monthly_income',
    'address'
]
```

### Relationships

**Get Parent Senior Citizen:**
```php
$member = FamilyMember::find($id);
$senior = $member->seniorCitizen;
```

---

## Query Examples

### Get All Pensioners Aged 70-79

```php
$seniors = SeniorCitizen::where('is_pensioner', true)
    ->where('age_range', '70-79')
    ->get();
```

### Get Senior Citizens with Disabilities

```php
$seniors = SeniorCitizen::where('with_disability', true)
    ->with('familyMembers')
    ->paginate(10);
```

### Get Indigent Senior Citizens by Barangay

```php
$seniors = SeniorCitizen::where('is_indigent', true)
    ->where('barangay', 'Barangay1')
    ->get();
```

### Search with Multiple Criteria

```php
$seniors = SeniorCitizen::where('is_pensioner', true)
    ->where(function($q) {
        $q->where('firstname', 'like', '%john%')
          ->orWhere('lastname', 'like', '%doe%');
    })
    ->get();
```

### Get Senior Citizen with Family Members

```php
$senior = SeniorCitizen::with('familyMembers')
    ->find($id);

// Access family members
foreach ($senior->familyMembers as $member) {
    echo $member->name . ' - ' . $member->relationship;
}
```

### Get Total Family Income for Age Group

```php
$seniors = SeniorCitizen::where('age_range', '60-69')->get();

$avgFamilyIncome = $seniors->average(function($senior) {
    return $senior->getTotalFamilyIncome();
});
```

---

## Error Handling

### Validation Errors

Form validation errors display with:
- Field name highlighted
- Error message in red
- Input value preserved for resubmission

### Unique Constraint Violation (OSCA ID)

If duplicate OSCA ID submitted:
```
× The osca id has already been taken.
```

### Archive/Restore Errors

- Soft-deleted records still appear in archive view
- Restore cascades family members
- Delete permanently removes all related family members

---

## Response Examples

### Successful Store

```
Redirect: /senior-citizens
Status: success
Message: "Senior citizen added successfully!"
```

### Validation Error

```
Redirect: /senior-citizens/create
Status: error
Errors: [
    'osca_id' => ['The osca_id has already been taken'],
    'firstname' => ['The firstname field is required']
]
Old Values: Preserved for resubmission
```

---

## Performance Considerations

### Pagination
- 10 records per page by default
- Adjustable via `paginate(n)`

### N+1 Query Prevention
```php
// Good
$seniors = SeniorCitizen::with('familyMembers')->paginate(10);

// Avoid (causes N+1)
$seniors = SeniorCitizen::paginate(10);
foreach ($seniors as $senior) {
    $count = $senior->familyMembers->count();  // N queries
}
```

### Indexes
- `osca_id` (unique)
- `senior_citizen_id` (FK in family_members)
- `age_range` (for filtering)
- `is_pensioner`, `is_indigent`, `with_disability` (boolean filters)

---

**Reference Guide Version:** 1.0  
**Last Updated:** February 24, 2026

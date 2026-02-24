# SCIMS - OSCA Intake Form Implementation Guide

## Overview
Complete redesign of the Senior Citizen module to match the official OSCA (Office of the Senior Citizen Affairs) Intake Form with comprehensive data collection and family composition tracking.

## Completed Implementation

### ✅ 1. Database Migrations

#### Family Members Table (`2026_02_24_create_family_members_table.php`)
- `id` - Primary key
- `senior_citizen_id` - Foreign key to senior_citizens
- `name` - Family member name
- `relationship` - Relationship to senior citizen
- `age` - Age of family member
- `civil_status` - Single, Married, Widowed, etc.
- `occupation` - Job/occupation
- `monthly_income` - Monthly earnings (decimal)
- `address` - Address (if different from senior citizen)
- Soft deletes for data integrity

#### Extended Senior Citizens Table (`2026_02_24_update_senior_citizens_osca_form.php`)

**Personal / Basic Information:**
- `extension_name` - Jr., Sr., III, IV suffixes
- `place_of_birth` - City/Municipality, Province
- `civil_status` - Single, Married, Widowed, Divorced, Separated, Other
- `citizenship` - Default: Filipino
- `religion` - Religious affiliation
- `educational_attainment` - No Formal, Elementary, High School, Vocational, College, Post-Graduate

**Health Condition Section:**
- `with_disability` - Boolean flag
- `type_of_disability` - Visual, Hearing, Motor, etc.
- `bedridden` - Boolean flag
- `with_assistive_device` - Boolean flag
- `type_of_assistive_device` - Wheelchair, Walker, Cane, etc.
- `with_critical_illness` - Boolean flag
- `specify_illness` - Text description of illness
- `philhealth_member` - Boolean flag
- `philhealth_id` - PhilHealth ID number

**Source of Income Section:**
- `is_pensioner` - Boolean flag
- `pension_type` - SSS, GSIS, PVAO, Private, Others
- `monthly_pension_amount` - Decimal amount
- `other_income_source` - Remittances, self-employment, rentals, etc.
- `total_monthly_income` - Total monthly income (decimal)

**Classification:**
- `is_indigent` - Boolean flag for indigent classification
- `age_range` - Computed: 60-69, 70-79, 80+

---

### ✅ 2. Models & Relationships

#### FamilyMember Model (`app/Models/FamilyMember.php`)
```php
public function seniorCitizen()
{
    return $this->belongsTo(SeniorCitizen::class);
}
```

#### SeniorCitizen Model Updates (`app/Models/SeniorCitizen.php`)
```php
// One-to-many relationship
public function familyMembers()
{
    return $this->hasMany(FamilyMember::class);
}

// Calculate total family income
public function getTotalFamilyIncome()
{
    $seniorIncome = $this->total_monthly_income ?? 0;
    $familyIncome = $this->familyMembers()->sum('monthly_income');
    return $seniorIncome + $familyIncome;
}

// Auto-compute age from date of birth
public function calculateAge()
{
    if ($this->date_of_birth) {
        $birthDate = $this->date_of_birth;
        $today = now();
        $age = $today->diffInYears($birthDate);
        
        // Set age range
        if ($age >= 60 && $age < 70) {
            $this->age_range = '60-69';
        } elseif ($age >= 70 && $age < 80) {
            $this->age_range = '70-79';
        } elseif ($age >= 80) {
            $this->age_range = '80+';
        }
        
        $this->age = $age;
    }
}
```

---

### ✅ 3. Controller Logic

#### Enhanced SeniorCitizenController (`app/Http/Controllers/SeniorCitizenController.php`)

**Key Features:**

1. **Advanced Filtering in `index()` method:**
   - Search by name, OSCA ID, contact, address
   - Filter by sex, barangay
   - Filter by classification:
     - `pensioners` - is_pensioner = true
     - `indigent` - is_indigent = true
     - `with_disability` - with_disability = true
     - `bedridden` - bedridden = true
     - `critical_illness` - with_critical_illness = true
   - Filter by age range (60-69, 70-79, 80+)
   - Legacy pension filters for backward compatibility

2. **Comprehensive Validation in `store()` method:**
   - All OSCA form fields validated
   - Automatic age calculation from date of birth
   - Full name generation from parts
   - Family member cascade creation

3. **Family Member Management:**
   - Create multiple family members simultaneously
   - Update/delete family members on senior citizen update
   - Store all family member fields with proper validation

---

### ✅ 4. Blade Form Structure (`resources/views/senior-citizens/create.blade.php`)

**5 Major Sections:**

#### Section 1️⃣: Personal / Basic Information
- OSCA ID Number (required, unique)
- Last Name, First Name, Middle Name, Extension Name
- Complete Address (textarea)
- Barangay (dropdown, required)
- Contact Number
- Date of Birth (required, auto-computes age & age range)
- Place of Birth
- Sex (required: Male, Female, Other)
- Civil Status (Single, Married, Widowed, Divorced, Separated, Other)
- Citizenship (default: Filipino)
- Religion
- Educational Attainment (6 levels)

#### Section 2️⃣: Health Condition
- **With Disability** - Checkbox with conditional field for type
- **Bedridden** - Simple checkbox
- **With Assistive Device** - Checkbox with conditional field for type
- **With Critical Illness** - Checkbox with textarea for details (red highlight)
- **PhilHealth Membership** - Checkbox with conditional field for ID (green highlight)

#### Section 3️⃣: Source of Income
- **Is a Pensioner** - Checkbox with conditional fields:
  - Pension Type (SSS, GSIS, PVAO, Private, Others)
  - Monthly Pension Amount
- **Other Income Source** - Textarea
- **Total Monthly Income** - Large highlighted field (purple)
- **Mark as Indigent** - Checkbox flag

#### Section 4️⃣: Family Composition
- Dynamic family member rows (can add/remove unlimited)
- Fields per family member:
  - Name, Relationship, Age
  - Civil Status, Occupation
  - Monthly Income, Address
- "+ Add Family Member" button with JavaScript

#### Section 5️⃣: Additional Information & Remarks
- Remarks dropdown (from Constants\Remarks list)
- On Waitlist checkbox
- Social Pension Recipient checkbox

**Features:**
- Responsive grid layout (1 col mobile, 2-4 cols desktop)
- Color-coded sections (blue, yellow, red, green, purple)
- Conditional field display (toggles based on checkboxes)
- Dark mode support
- Form validation with error display
- JavaScript family member management

---

### ✅ 5. Filtering Implementation

The `index()` method now supports:

```php
// By Classification
?classification=pensioners       // Filter pensioners
?classification=indigent        // Filter indigent/low income
?classification=with_disability // Filter with disabilities
?classification=bedridden       // Filter bedridden
?classification=critical_illness// Filter with critical illness

// By Age Range
?age_range=60-69  // Ages 60-69
?age_range=70-79  // Ages 70-79
?age_range=80+    // Ages 80 and above

// Combined with existing filters
?search=john&classification=pensioners&age_range=70-79
```

---

## All OSCA Form Fields Implemented

✅ **Personal Information (11 fields)**
- OSCA ID Number
- Last Name, First Name, Middle Name
- Extension Name
- Complete Address
- Barangay
- Contact Number
- Date of Birth (with auto age)
- Place of Birth
- Sex
- Civil Status
- Citizenship
- Religion
- Educational Attainment

✅ **Health Condition (9 fields)**
- With Disability + Type
- Bedridden
- With Assistive Device + Type
- With Critical Illness + Specification
- PhilHealth Membership + ID

✅ **Source of Income (5 fields)**
- Is Pensioner
- Pension Type
- Monthly Pension Amount
- Other Income Source
- Total Monthly Income

✅ **Family Composition (7 fields per member)**
- Name
- Relationship
- Age
- Civil Status
- Occupation
- Monthly Income
- Address

✅ **Classification/Categorization (5 flags)**
- Pensioners (is_pensioner)
- Indigent/Low Income (is_indigent)
- With Disability (with_disability)
- Bedridden (bedridden)
- With Critical Illness (with_critical_illness)

✅ **Age Range Classification (3 ranges)**
- 60-69, 70-79, 80+ (auto-computed)

---

## Additional Features

### 1. Auto-Computed Fields
- **Age** - Automatically calculated from date_of_birth
- **Age Range** - Automatically classified (60-69, 70-79, 80+)
- **Full Name** - Generated from name parts

### 2. Relationship Management
- One Senior Citizen → Many Family Members (1:M)
- Cascade soft deletes for data protection
- Total family income calculation

### 3. Backward Compatibility
- Existing legacy fields maintained:
  - sss, gsis, pvao, family_pension, brgy_official
  - waitlist, social_pension
  - remarks
- Old filters still work while new filters are available

### 4. Data Validation
- Comprehensive validation rules for all fields
- Email-style field labeling
- Unique OSCA ID constraint
- Date validation (must be before today)
- Numeric validations for income fields

### 5. User Interface
- Professional OSCA form header
- Color-coded sections for quick navigation
- Conditional field visibility
- Responsive design (mobile-friendly)
- Dark mode support
- Clear required field indicators (*)

---

## Database Schema Summary

```
senior_citizens (Extended)
├── id
├── osca_id (unique)
├── lastname, firstname, middlename, extension_name
├── date_of_birth, place_of_birth, age, age_range
├── sex, civil_status, citizenship, religion
├── educational_attainment
├── address, barangay, contact_number
├── with_disability, type_of_disability
├── bedridden
├── with_assistive_device, type_of_assistive_device
├── with_critical_illness, specify_illness
├── philhealth_member, philhealth_id
├── is_pensioner, pension_type
├── monthly_pension_amount
├── other_income_source
├── total_monthly_income
├── is_indigent
├── [Legacy fields: sss, gsis, pvao, family_pension, brgy_official, waitlist, social_pension, remarks]
└── [Timestamps & soft deletes]

family_members
├── id
├── senior_citizen_id (FK → senior_citizens)
├── name
├── relationship
├── age
├── civil_status
├── occupation
├── monthly_income
├── address
└── [Timestamps & soft deletes]
```

---

## Next Steps (Optional)

1. **Create Edit Form** - Mirror create.blade.php for updates
2. **Create Show View** - Display senior citizen with all family members
3. **Generate Reports** - Filter and export data by classification
4. **PDF Export** - Export OSCA intake form as PDF
5. **Dashboard Statistics** - Show counts by classification, age range
6. **Family Member Index** - View all family members for a senior citizen

---

## Quick Test Guide

1. Navigate to `senior-citizens/create`
2. Fill Section 1: Basic info (OSCA ID, names, address, date of birth)
3. Fill Section 2: Check health conditions as applicable
4. Fill Section 3: Add income information
5. Fill Section 4: Click "+ Add Family Member" to add relatives
6. Fill Section 5: Add remarks if needed
7. Click "Save Senior Citizen"
8. View index page with new filtering options

---

**Implementation Complete ✓**
All OSCA Intake Form fields implemented with proper relationships, validation, and filtering!

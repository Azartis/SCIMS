<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Senior Citizen - OSCA Intake Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('senior-citizens.update', $seniorCitizen) }}" method="POST" id="oscaForm">
                @csrf
                @method('PUT')

                <!-- HEADER SECTION -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b-2 border-blue-500">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            OFFICE OF THE SENIOR CITIZEN AFFAIRS (OSCA) INTAKE FORM
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            Complete all fields marked with asterisk (*) are required
                        </p>
                    </div>
                </div>

                <!-- SECTION 1: PERSONAL / BASIC INFORMATION -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                            1️⃣ PERSONAL / BASIC INFORMATION
                        </h3>

                        <!-- OSCA ID Number -->
                        <div class="mb-6">
                            <x-input-label for="osca_id" :value="__('OSCA ID Number *')" />
                            <x-text-input id="osca_id" class="block mt-1 w-full" type="text" name="osca_id" :value="old('osca_id', $seniorCitizen->osca_id)" required autofocus />
                            <x-input-error :messages="$errors->get('osca_id')" class="mt-2" />
                        </div>

                        <!-- Name Section (Last, First, Middle, Extension) -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <x-input-label for="lastname" :value="__('Last Name *')" />
                                <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $seniorCitizen->lastname)" required />
                                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="firstname" :value="__('First Name *')" />
                                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $seniorCitizen->firstname)" required />
                                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="middlename" :value="__('Middle Name')" />
                                <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename', $seniorCitizen->middlename)" />
                                <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="extension_name" :value="__('Extension (Jr., Sr., III)')" />
                                <x-text-input id="extension_name" class="block mt-1 w-full" type="text" name="extension_name" :value="old('extension_name', $seniorCitizen->extension_name)" placeholder="Jr., Sr., III, IV" />
                                <x-input-error :messages="$errors->get('extension_name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <x-input-label for="address" :value="__('Complete Address *')" />
                            <textarea id="address" name="address" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" rows="2" required>{{ old('address', $seniorCitizen->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Barangay -->
                        <div class="mb-6">
                            <x-input-label for="barangay" :value="__('Barangay *')" />
                            <select id="barangay" name="barangay" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Select Barangay --</option>
                                @foreach(\App\Constants\Barangay::list() as $barangay)
                                    <option value="{{ $barangay }}" @selected(old('barangay', $seniorCitizen->barangay) === $barangay)>{{ $barangay }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-6">
                            <x-input-label for="contact_number" :value="__('Contact Number')" />
                            <x-text-input id="contact_number" class="block mt-1 w-full" type="tel" name="contact_number" :value="old('contact_number', $seniorCitizen->contact_number)" placeholder="09XX-XXX-XXXX" />
                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                        </div>

                        <!-- Date of Birth & Place of Birth -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth *')" />
                                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth', $seniorCitizen->date_of_birth ? $seniorCitizen->date_of_birth->format('Y-m-d') : '')" required onchange="computeAge()" />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="place_of_birth" :value="__('Place of Birth')" />
                                <x-text-input id="place_of_birth" class="block mt-1 w-full" type="text" name="place_of_birth" :value="old('place_of_birth', $seniorCitizen->place_of_birth)" placeholder="City/Municipality, Province" />
                                <x-input-error :messages="$errors->get('place_of_birth')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sex & Civil Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="sex" :value="__('Sex *')" />
                                <select id="sex" name="sex" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Select Sex --</option>
                                    <option value="Male" @selected(old('sex', $seniorCitizen->sex) === 'Male')>Male</option>
                                    <option value="Female" @selected(old('sex', $seniorCitizen->sex) === 'Female')>Female</option>
                                    <option value="Other" @selected(old('sex', $seniorCitizen->sex) === 'Other')>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="civil_status" :value="__('Civil Status')" />
                                <select id="civil_status" name="civil_status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Select Civil Status --</option>
                                    <option value="Single" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Single')>Single</option>
                                    <option value="Married" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Married')>Married</option>
                                    <option value="Widowed" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Widowed')>Widowed</option>
                                    <option value="Divorced" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Divorced')>Divorced</option>
                                    <option value="Separated" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Separated')>Separated</option>
                                    <option value="Other" @selected(old('civil_status', $seniorCitizen->civil_status) === 'Other')>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Citizenship & Religion -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="citizenship" :value="__('Citizenship')" />
                                <x-text-input id="citizenship" class="block mt-1 w-full" type="text" name="citizenship" :value="old('citizenship', $seniorCitizen->citizenship ?? 'Filipino')" />
                                <x-input-error :messages="$errors->get('citizenship')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="religion" :value="__('Religion')" />
                                <x-text-input id="religion" class="block mt-1 w-full" type="text" name="religion" :value="old('religion', $seniorCitizen->religion)" />
                                <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Educational Attainment -->
                        <div class="mb-6">
                            <x-input-label for="educational_attainment" :value="__('Educational Attainment')" />
                            <select id="educational_attainment" name="educational_attainment" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Level --</option>
                                <option value="No Formal Education" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'No Formal Education')>No Formal Education</option>
                                <option value="Elementary" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'Elementary')>Elementary</option>
                                <option value="High School" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'High School')>High School</option>
                                <option value="Vocational" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'Vocational')>Vocational</option>
                                <option value="College" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'College')>College</option>
                                <option value="Post-Graduate" @selected(old('educational_attainment', $seniorCitizen->educational_attainment) === 'Post-Graduate')>Post-Graduate</option>
                            </select>
                            <x-input-error :messages="$errors->get('educational_attainment')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: HEALTH CONDITION -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                            2️⃣ HEALTH CONDITION
                        </h3>

                        <!-- With Disability -->
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="with_disability" name="with_disability" value="1" @checked(old('with_disability', $seniorCitizen->with_disability)) onchange="toggleElement('type_of_disability_div')" class="rounded">
                                    <span class="text-gray-900 dark:text-gray-100">With Disability</span>
                                </label>
                            </div>
                            <div id="type_of_disability_div" style="display: {{ old('with_disability', $seniorCitizen->with_disability) ? 'block' : 'none' }}">
                                <x-input-label for="type_of_disability" :value="__('Type of Disability')" />
                                <x-text-input id="type_of_disability" class="block mt-1 w-full" type="text" name="type_of_disability" :value="old('type_of_disability', $seniorCitizen->type_of_disability)" placeholder="e.g., Visual, Hearing, Motor..." />
                                <x-input-error :messages="$errors->get('type_of_disability')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Bedridden -->
                        <div class="mb-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="bedridden" name="bedridden" value="1" @checked(old('bedridden', $seniorCitizen->bedridden)) class="rounded">
                                <span class="text-gray-900 dark:text-gray-100">Bedridden</span>
                            </label>
                        </div>

                        <!-- With Assistive Device -->
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="with_assistive_device" name="with_assistive_device" value="1" @checked(old('with_assistive_device', $seniorCitizen->with_assistive_device)) onchange="toggleElement('type_of_assistive_device_div')" class="rounded">
                                    <span class="text-gray-900 dark:text-gray-100">With Assistive Device</span>
                                </label>
                            </div>
                            <div id="type_of_assistive_device_div" style="display: {{ old('with_assistive_device', $seniorCitizen->with_assistive_device) ? 'block' : 'none' }}">
                                <x-input-label for="type_of_assistive_device" :value="__('Type of Assistive Device')" />
                                <x-text-input id="type_of_assistive_device" class="block mt-1 w-full" type="text" name="type_of_assistive_device" :value="old('type_of_assistive_device', $seniorCitizen->type_of_assistive_device)" placeholder="e.g., Wheelchair, Walker, Cane..." />
                                <x-input-error :messages="$errors->get('type_of_assistive_device')" class="mt-2" />
                            </div>
                        </div>

                        <!-- With Critical Illness -->
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="with_critical_illness" name="with_critical_illness" value="1" @checked(old('with_critical_illness', $seniorCitizen->with_critical_illness)) onchange="toggleElement('specify_illness_div')" class="rounded">
                                    <span class="text-gray-900 dark:text-gray-100 font-semibold">With Critical Illness</span>
                                </label>
                            </div>
                            <div id="specify_illness_div" style="display: {{ old('with_critical_illness', $seniorCitizen->with_critical_illness) ? 'block' : 'none' }}">
                                <x-input-label for="specify_illness" :value="__('Specify Illness')" />
                                <textarea id="specify_illness" name="specify_illness" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" rows="3" placeholder="Describe the critical illness...">{{ old('specify_illness', $seniorCitizen->specify_illness) }}</textarea>
                                <x-input-error :messages="$errors->get('specify_illness')" class="mt-2" />
                            </div>
                        </div>

                        <!-- PhilHealth Membership -->
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="philhealth_member" name="philhealth_member" value="1" @checked(old('philhealth_member', $seniorCitizen->philhealth_member)) onchange="toggleElement('philhealth_id_div')" class="rounded">
                                    <span class="text-gray-900 dark:text-gray-100">PhilHealth Member</span>
                                </label>
                            </div>
                            <div id="philhealth_id_div" style="display: {{ old('philhealth_member', $seniorCitizen->philhealth_member) ? 'block' : 'none' }}">
                                <x-input-label for="philhealth_id" :value="__('PhilHealth ID Number')" />
                                <x-text-input id="philhealth_id" class="block mt-1 w-full" type="text" name="philhealth_id" :value="old('philhealth_id', $seniorCitizen->philhealth_id)" placeholder="Enter PhilHealth ID" />
                                <x-input-error :messages="$errors->get('philhealth_id')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: SOURCE OF INCOME -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                            3️⃣ SOURCE OF INCOME
                        </h3>

                        <!-- Pensioner Status -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="is_pensioner" name="is_pensioner" value="1" @checked(old('is_pensioner', $seniorCitizen->is_pensioner)) onchange="toggleElement('pension_details_div')" class="rounded">
                                    <span class="text-gray-900 dark:text-gray-100 font-semibold">Is a Pensioner</span>
                                </label>
                            </div>
                            <div id="pension_details_div" style="display: {{ old('is_pensioner', $seniorCitizen->is_pensioner) ? 'block' : 'none' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="pension_type" :value="__('Type of Pension')" />
                                        <select id="pension_type" name="pension_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Select Type --</option>
                                            <option value="SSS" @selected(old('pension_type', $seniorCitizen->pension_type) === 'SSS')>SSS (Social Security System)</option>
                                            <option value="GSIS" @selected(old('pension_type', $seniorCitizen->pension_type) === 'GSIS')>GSIS (Government Service Insurance)</option>
                                            <option value="PVAO" @selected(old('pension_type', $seniorCitizen->pension_type) === 'PVAO')>PVAO (Veterans Affairs)</option>
                                            <option value="Private" @selected(old('pension_type', $seniorCitizen->pension_type) === 'Private')>Private Pension</option>
                                            <option value="Others" @selected(old('pension_type', $seniorCitizen->pension_type) === 'Others')>Others</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('pension_type')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="monthly_pension_amount" :value="__('Monthly Pension Amount (₱)')" />
                                        <x-text-input id="monthly_pension_amount" class="block mt-1 w-full" type="number" name="monthly_pension_amount" :value="old('monthly_pension_amount', $seniorCitizen->monthly_pension_amount)" step="0.01" min="0" placeholder="0.00" />
                                        <x-input-error :messages="$errors->get('monthly_pension_amount')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Income Source -->
                        <div class="mb-6">
                            <x-input-label for="other_income_source" :value="__('Other Income Source (if any)')" />
                            <textarea id="other_income_source" name="other_income_source" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" rows="2" placeholder="e.g., Remittance, Self-employment, Rentals...">{{ old('other_income_source', $seniorCitizen->other_income_source) }}</textarea>
                            <x-input-error :messages="$errors->get('other_income_source')" class="mt-2" />
                        </div>

                        <!-- Total Monthly Income -->
                        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                            <x-input-label for="total_monthly_income" :value="__('Total Monthly Income (₱)')" />
                            <x-text-input id="total_monthly_income" class="block mt-1 w-full text-lg font-bold" type="number" name="total_monthly_income" :value="old('total_monthly_income', $seniorCitizen->total_monthly_income)" step="0.01" min="0" placeholder="0.00" />
                            <p class="text-sm text-purple-700 dark:text-purple-300 mt-2">This will help classify as indigent if below poverty threshold</p>
                            <x-input-error :messages="$errors->get('total_monthly_income')" class="mt-2" />
                        </div>

                        <!-- Mark as Indigent -->
                        <div class="mb-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="is_indigent" name="is_indigent" value="1" @checked(old('is_indigent', $seniorCitizen->is_indigent)) class="rounded">
                                <span class="text-gray-900 dark:text-gray-100">Mark as Indigent / Low Income</span>
                            </label>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Check if total monthly income is below poverty threshold</p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: FAMILY COMPOSITION -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                            4️⃣ FAMILY COMPOSITION
                        </h3>

                        <div id="family_members_container">
                            <!-- Display existing family members -->
                            @forelse($seniorCitizen->familyMembers as $index => $member)
                                <div class="family-member-row mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-blue-500" data-index="{{ $index }}">
                                    <button type="button" class="float-right mb-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="removeFamilyMember(this)">Remove</button>
                                    <div class="clear-both">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                                <input type="text" name="family_members[{{ $index }}][name]" value="{{ $member->name }}" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship</label>
                                                <input type="text" name="family_members[{{ $index }}][relationship]" value="{{ $member->relationship }}" placeholder="Child, Grandchild, Sibling..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                                                <input type="number" name="family_members[{{ $index }}][age]" value="{{ $member->age }}" min="0" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Civil Status</label>
                                                <input type="text" name="family_members[{{ $index }}][civil_status]" value="{{ $member->civil_status }}" placeholder="Single, Married..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Occupation</label>
                                                <input type="text" name="family_members[{{ $index }}][occupation]" value="{{ $member->occupation }}" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Income (₱)</label>
                                                <input type="number" name="family_members[{{ $index }}][monthly_income]" value="{{ $member->monthly_income }}" step="0.01" min="0" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                                <input type="text" name="family_members[{{ $index }}][address]" value="{{ $member->address }}" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- No family members -->
                            @endforelse
                        </div>

                        <button type="button" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600" onclick="addFamilyMember()">
                            + Add Family Member
                        </button>
                    </div>
                </div>

                <!-- SECTION 5: ADDITIONAL INFORMATION & REMARKS -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                            5️⃣ ADDITIONAL INFORMATION & REMARKS
                        </h3>

                        <div class="mb-6">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <select id="remarks" name="remarks" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Remark --</option>
                                @foreach(\App\Constants\Remarks::list() as $remark)
                                    <option value="{{ $remark }}" @selected(old('remarks', $seniorCitizen->remarks) === $remark)>{{ $remark }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" id="waitlist" name="waitlist" value="1" @checked(old('waitlist', $seniorCitizen->waitlist)) class="rounded">
                                <span class="text-gray-900 dark:text-gray-100">On Waitlist</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" id="social_pension" name="social_pension" value="1" @checked(old('social_pension', $seniorCitizen->social_pension)) class="rounded">
                                <span class="text-gray-900 dark:text-gray-100">Social Pension Recipient</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex gap-4 mb-6">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Update Senior Citizen
                    </button>
                    <a href="{{ route('senior-citizens.index') }}" class="px-6 py-3 bg-gray-400 text-white rounded-lg font-semibold hover:bg-gray-500 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let familyMemberCount = {{ count($seniorCitizen->familyMembers) }};

        function computeAge() {
            const dobInput = document.getElementById('date_of_birth');
            if (!dobInput.value) return;

            const dob = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            console.log('Computed age:', age);
        }

        function toggleElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = element.style.display === 'none' ? 'block' : 'none';
            }
        }

        function addFamilyMember() {
            const container = document.getElementById('family_members_container');
            const index = familyMemberCount++;

            const html = `
                <div class="family-member-row mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-blue-500" data-index="${index}">
                    <button type="button" class="float-right mb-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="removeFamilyMember(this)">Remove</button>
                    <div class="clear-both">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <input type="text" name="family_members[${index}][name]" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship</label>
                                <input type="text" name="family_members[${index}][relationship]" placeholder="Child, Grandchild, Sibling..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                                <input type="number" name="family_members[${index}][age]" min="0" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Civil Status</label>
                                <input type="text" name="family_members[${index}][civil_status]" placeholder="Single, Married..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Occupation</label>
                                <input type="text" name="family_members[${index}][occupation]" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Income (₱)</label>
                                <input type="number" name="family_members[${index}][monthly_income]" step="0.01" min="0" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                <input type="text" name="family_members[${index}][address]" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        }

        function removeFamilyMember(button) {
            const row = button.closest('.family-member-row');
            if (row) {
                row.remove();
            }
        }
    </script>
</x-app-layout>

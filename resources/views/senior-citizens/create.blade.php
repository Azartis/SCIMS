<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Senior Citizen - OSCA Intake Form') }}
        </h2>
    </x-slot>

    <div class="py-3 px-4 sm:px-0">
        <div class="max-w-6xl mx-auto">
            <form action="{{ route('senior-citizens.store') }}" method="POST" id="oscaForm" onsubmit="prepareFormSubmit(event)">
                @csrf

                <!-- Header Note -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 border-l-4 border-blue-600 p-2 rounded-lg mb-3 text-xs shadow-sm">
                    <p class="text-gray-700 dark:text-gray-100"><span class="font-semibold">Note:</span> Fields marked <span class="text-red-600 font-bold">*</span> are required</p>
                </div>

                <!-- Personal Information Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-3 border-t-4 border-blue-600">
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                        👤 Personal Information
                    </h2>
                    
                    <!-- Name and ID Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="osca_id" :value="__('OSCA ID *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="osca_id" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="osca_id" :value="old('osca_id')" required />
                            <x-input-error :messages="$errors->get('osca_id')" class="mt-0.5 text-xs" />
                        </div>
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="national_id" :value="__('National ID *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="national_id" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="national_id" placeholder="0000-0000-0000-0000" :value="old('national_id')" required />
                            <x-input-error :messages="$errors->get('national_id')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="lastname" :value="__('Last Name *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="lastname" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="lastname" :value="old('lastname')" required />
                            <x-input-error :messages="$errors->get('lastname')" class="mt-0.5 text-xs" />
                        </div>
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="firstname" :value="__('First Name *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="firstname" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="firstname" :value="old('firstname')" required />
                            <x-input-error :messages="$errors->get('firstname')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="middlename" :value="__('Middle Name')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="middlename" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="middlename" :value="old('middlename')" />
                            <x-input-error :messages="$errors->get('middlename')" class="mt-0.5 text-xs" />
                        </div>
                        <div></div>
                    </div>

                    <!-- Demographics Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-700">
                            <x-input-label for="date_of_birth" :value="__('Date of Birth *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="date" name="date_of_birth" :value="old('date_of_birth')" required onchange="computeAge()" />
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Format: YYYY-MM-DD</p>
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-0.5 text-xs" />
                        </div>
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-700">
                            <x-input-label for="sex" :value="__('Sex *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <select id="sex" name="sex" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" required>
                                <option value="">-- Select --</option>
                                <option value="Male" @selected(old('sex')==='Male')>Male</option>
                                <option value="Female" @selected(old('sex')==='Female')>Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('sex')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-700">
                            <x-input-label for="civil_status" :value="__('Civil Status')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <select id="civil_status" name="civil_status" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md">
                                <option value="">-- Select --</option>
                                <option value="Single" @selected(old('civil_status')==='Single')>Single</option>
                                <option value="Married" @selected(old('civil_status')==='Married')>Married</option>
                                <option value="Widowed" @selected(old('civil_status')==='Widowed')>Widowed</option>
                            </select>
                            <x-input-error :messages="$errors->get('civil_status')" class="mt-0.5 text-xs" />
                        </div>
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-700">
                            <x-input-label for="barangay" :value="__('Barangay *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <select id="barangay" name="barangay" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" required>
                                <option value="">-- Select Barangay --</option>
                                @foreach(\App\Constants\Barangay::list() as $b)
                                    <option value="{{ $b }}" @selected(old('barangay')===$b)>{{ $b }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('barangay')" class="mt-0.5 text-xs" />
                        </div>
                    </div>

                    <!-- Address and Contact Section -->
                    <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-700 mb-2">
                        <x-input-label for="address" :value="__('Municipal *')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                        <textarea id="address" name="address" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" rows="2" placeholder="Enter municipal address" required>{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-0.5 text-xs" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded border border-green-200 dark:border-green-700">
                            <x-input-label for="contact_number" :value="__('Contact Number')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="contact_number" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="tel" name="contact_number" placeholder="e.g., 09XX-XXX-XXXX" :value="old('contact_number')" />
                        </div>
                        <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded border border-green-200 dark:border-green-700">
                            <x-input-label for="religion" :value="__('Email Address')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                            <x-text-input id="religion" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="email" name="religion" placeholder="Enter email (optional)" :value="old('religion')" />
                        </div>
                    </div>
                </div>

                <!-- Health & Benefits Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-3 border-t-4 border-green-600">
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                        🏥 Health & Benefits Information
                    </h2>
                    
                    <!-- Health Basics Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <!-- Bedridden -->
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label :value="__('Bedridden Status')" class="block text-xs font-semibold text-gray-800 dark:text-gray-100 mb-1" />
                            <div class="space-y-0.5">
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" name="bedridden" value="1" class="form-radio w-3 h-3" @checked(old('bedridden')==='1' || old('bedridden')===1)>
                                    <span class="ms-2">Yes</span>
                                </label>
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" name="bedridden" value="0" class="form-radio w-3 h-3" @checked(old('bedridden')==='0' || old('bedridden')===0 || old('bedridden')===null)>
                                    <span class="ms-2">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Assistive Device (Independent) -->
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-700">
                            <x-input-label :value="__('Assistive Device?')" class="block text-xs font-semibold text-gray-800 dark:text-gray-100 mb-1" />
                            <div class="space-y-0.5">
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" name="with_assistive_device" value="1" class="form-radio w-3 h-3 assistive-device-radio" @checked(old('with_assistive_device')==='1' || old('with_assistive_device')===1)>
                                    <span class="ms-2">Yes</span>
                                </label>
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" name="with_assistive_device" value="0" class="form-radio w-3 h-3 assistive-device-radio" @checked(old('with_assistive_device')==='0' || old('with_assistive_device')===0 || old('with_assistive_device')===null)>
                                    <span class="ms-2">No</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Specify Assistive Device -->
                    <div class="mb-2 p-2 bg-amber-100 dark:bg-amber-900/30 rounded border border-amber-300 dark:border-amber-700" id="specify_device_container" style="display: none;">
                        <x-input-label for="type_of_assistive_device" :value="__('Specify Device Type')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                        <x-text-input id="type_of_assistive_device" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="type_of_assistive_device" placeholder="e.g., Wheelchair, Crutches, Walker" :value="old('type_of_assistive_device')" />
                    </div>

                    <!-- Disability Section -->
                    <div class="mb-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                        <x-input-label :value="__('Disability Status')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                        <div>
                            <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input type="checkbox" name="with_disability" value="1" class="form-checkbox rounded" @checked(old('with_disability'))>
                                <span class="ms-2 text-xs text-gray-700 dark:text-gray-200">Has Disability</span>
                            </label>
                        </div>
                    </div>

                    <!-- Type of Disability -->
                    <div class="mb-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-700" id="cause_disability_container" style="display: none;">
                        <x-input-label for="type_of_disability" :value="__('Type of Disability')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                        <select id="type_of_disability" name="type_of_disability" class="block w-full text-xs border-gray-300 dark:border-gray-600 rounded-md">
                            <option value="">-- Select Type --</option>
                            <option value="Deaf" @selected(old('type_of_disability')==='Deaf')>Deaf or Hard of Hearing</option>
                            <option value="Intellectual Disability" @selected(old('type_of_disability')==='Intellectual Disability')>Intellectual Disability</option>
                            <option value="Learning Disability" @selected(old('type_of_disability')==='Learning Disability')>Learning Disability</option>
                            <option value="Mental Disability" @selected(old('type_of_disability')==='Mental Disability')>Mental Disability</option>
                            <option value="Physical disability" @selected(old('type_of_disability')==='Physical disability')>Physical disability</option>
                            <option value="Psychosocial Disability" @selected(old('type_of_disability')==='Psychosocial Disability')>Psychosocial Disability</option>
                            <option value="Speech and language impairment" @selected(old('type_of_disability')==='Speech and language impairment')>Speech and language impairment</option>
                            <option value="Visual Disability" @selected(old('type_of_disability')==='Visual Disability')>Visual Disability</option>
                            <option value="Cancer" @selected(old('type_of_disability')==='Cancer')>Cancer</option>
                            <option value="Rare Disease" @selected(old('type_of_disability')==='Rare Disease')>Rare Disease</option>
                        </select>
                        <x-input-label for="cause_of_disability" :value="__('Cause of Disability')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1 mt-2" />
                        <div class="space-y-0.5">
                            <label class="flex items-center cursor-pointer text-xs">
                                <input type="radio" name="cause_of_disability" value="Congenital/Inborn" class="form-radio w-3 h-3" @checked(old('cause_of_disability')==='Congenital/Inborn')>
                                <span class="ms-2">Congenital/Inborn</span>
                            </label>
                            <label class="flex items-center cursor-pointer text-xs">
                                <input type="radio" name="cause_of_disability" value="Acquired" class="form-radio w-3 h-3" @checked(old('cause_of_disability')==='Acquired')>
                                <span class="ms-2">Acquired</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pensioner and Pension Type Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label :value="__('Is Pensioner?')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                            <div class="space-y-0.5">
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" id="pensioner_yes" name="is_pensioner" value="1" class="form-radio w-3 h-3 pensioner-radio" @checked(old('is_pensioner')==='1' || old('is_pensioner')===1)>
                                    <span class="ms-2">Yes</span>
                                </label>
                                <label class="flex items-center cursor-pointer text-xs">
                                    <input type="radio" id="pensioner_no" name="is_pensioner" value="0" class="form-radio w-3 h-3 pensioner-radio" @checked(old('is_pensioner')==='0' || old('is_pensioner')===0 || old('is_pensioner')===null)>
                                    <span class="ms-2">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Pension Type (shown when pensioner is yes) -->
                        <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded border-l-4 border-purple-500" id="pension_type_container" style="display: none;">
                            <x-input-label for="pension_type" :value="__('Pension Type')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                            <select id="pension_type" name="pension_type" class="block w-full text-xs border-gray-300 dark:border-gray-600 rounded-md">
                                <option value="">-- Select Pension Type --</option>
                                <option value="SSS" @selected(old('pension_type')==='SSS')>SSS (Social Security System)</option>
                                <option value="GSIS" @selected(old('pension_type')==='GSIS')>GSIS (Government Service Insurance System)</option>
                                <option value="PVAO" @selected(old('pension_type')==='PVAO')>PVAO (Philippine Veterans Affairs Office)</option>
                                <option value="Others" @selected(old('pension_type')==='Others')>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- PhilHealth ID and Source of Income -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="philhealth_id" :value="__('PhilHealth ID')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                            <x-text-input id="philhealth_id" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="philhealth_id" placeholder="Enter PhilHealth ID (optional)" :value="old('philhealth_id')" />
                        </div>
                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="source_of_income" :value="__('Source of Income')" class="block text-gray-800 dark:text-gray-100 font-semibold text-xs mb-1" />
                            <select id="source_of_income" name="source_of_income" class="block w-full text-xs border-gray-300 dark:border-gray-600 rounded-md">
                                <option value="">-- Select Source --</option>
                                <option value="Employment/Salary" @selected(old('source_of_income')==='Employment/Salary')>Employment/Salary</option>
                                <option value="Pension" @selected(old('source_of_income')==='Pension')>Pension</option>
                                <option value="Self-Employment" @selected(old('source_of_income')==='Self-Employment')>Self-Employment</option>
                                <option value="Remittance" @selected(old('source_of_income')==='Remittance')>Remittance</option>
                                <option value="Business" @selected(old('source_of_income')==='Business')>Business</option>
                                <option value="Government Assistance" @selected(old('source_of_income')==='Government Assistance')>Government Assistance</option>
                                <option value="Others" @selected(old('source_of_income')==='Others')>Others</option>
                            </select>
                        </div>
                    </div>

                    <!-- Specify Other Income Source (shown when Others is selected) -->
                    <div class="mb-2 p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded border border-indigo-300 dark:border-indigo-700" id="specify_income_container" style="display: none;">
                        <x-input-label for="other_income_source_specify" :value="__('Please Specify Other Income Source')" class="text-xs font-semibold text-gray-800 dark:text-gray-100" />
                        <x-text-input id="other_income_source_specify" class="block mt-1 w-full text-xs border-gray-300 dark:border-gray-600 rounded-md" type="text" name="other_income_source_specify" placeholder="e.g., Rental income, Savings interest, etc." :value="old('other_income_source_specify')" />
                    </div>
                </div>

                <!-- Family Members Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-3 border-t-4 border-yellow-600">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 pb-2 border-b border-gray-200 dark:border-gray-700 flex-1">
                            👨‍👩‍👧‍👦 Family Members
                        </h2>
                        <button type="button" onclick="addFamilyMember()" class="ml-3 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded shadow transition">
                            + Add
                        </button>
                    </div>
                    <div id="family_members_container" class="space-y-1"></div>
                    <input type="hidden" id="family_members_data" name="family_members_json" />
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 mb-3">
                    <button type="submit" class="px-5 py-1.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded shadow transition text-xs" onclick="prepareFormSubmit(event)">
                        Save
                    </button>
                    <a href="{{ route('senior-citizens.index') }}" class="px-5 py-1.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded shadow transition text-xs">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function computeAge(){var d=document.getElementById('date_of_birth');if(!d.value)return;var dob=new Date(d.value),t=new Date(),age=t.getFullYear()-dob.getFullYear(),m=t.getMonth()-dob.getMonth();if(m<0||(m===0&&t.getDate()<dob.getDate()))age--;console.log(age);}        
        let familyMemberCount=0;
        function addFamilyMember(){
            const c=document.getElementById('family_members_container');
            const i=familyMemberCount++;
            const h=`<div class="family-member-row p-2 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded border border-yellow-200 dark:border-yellow-700 shadow-sm" data-index="${i}">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-200">Member #${i+1}</h4>
                    <button type="button" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-bold text-sm" onclick="this.closest('.family-member-row').remove()">×</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <input type="text" name="family_members[${i}][name]" placeholder="Full Name" class="text-xs w-full border-gray-300 dark:border-gray-600 rounded" required>
                    <input type="text" name="family_members[${i}][relationship]" placeholder="Relationship" class="text-xs w-full border-gray-300 dark:border-gray-600 rounded" required>
                    <input type="number" name="family_members[${i}][age]" placeholder="Age" min="0" max="150" class="text-xs w-full border-gray-300 dark:border-gray-600 rounded">
                </div>
            </div>`;
            c.insertAdjacentHTML('beforeend',h);
        }
        
        // Disability checkbox
        const disabilityCheckbox = document.querySelector('input[name="with_disability"]');
        disabilityCheckbox.addEventListener('change', function() {
            document.getElementById('cause_disability_container').style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                document.getElementById('type_of_disability').value = '';
            }
        });

        // Assistive Device radio buttons (independent from bedridden)
        document.querySelectorAll('input[name="with_assistive_device"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('specify_device_container').style.display = this.value === '1' ? 'block' : 'none';
                if (this.value === '0') {
                    document.getElementById('type_of_assistive_device').value = '';
                }
            });
        });

        // Pensioner radio buttons
        document.querySelectorAll('input[name="is_pensioner"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('pension_type_container').style.display = this.value === '1' ? 'block' : 'none';
                if (this.value === '0') {
                    document.getElementById('pension_type').value = '';
                }
            });
        });

        // Source of Income select
        document.getElementById('source_of_income').addEventListener('change', function() {
            document.getElementById('specify_income_container').style.display = this.value === 'Others' ? 'block' : 'none';
            if (this.value !== 'Others') {
                document.getElementById('other_income_source_specify').value = '';
            }
        });

        // Pension Type select
        document.getElementById('pension_type').addEventListener('change', function() {
            document.getElementById('other_pension_container').style.display = this.value === 'other' ? 'block' : 'none';
            if (this.value !== 'other') {
                document.getElementById('other_pension_type').value = '';
            }
        });

        // PhilHealth radio buttons
        document.querySelectorAll('input[name="has_philhealth"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('philhealth_id').disabled = this.value === 'no';
                if (this.value === 'no') {
                    document.getElementById('philhealth_id').value = '';
                }
            });
        });

        // Prepare form submission with family members
        function prepareFormSubmit(event) {
            const familyMembers = [];
            document.querySelectorAll('.family-member-row').forEach(row => {
                const name = row.querySelector('input[placeholder="Full Name"]').value;
                if (name) {
                    familyMembers.push({
                        name: name,
                        relationship: row.querySelector('input[placeholder="Relationship"]').value,
                        age: row.querySelector('input[placeholder="Age"]').value
                    });
                }
            });
            // Store family members data in hidden field
            document.getElementById('family_members_data').value = JSON.stringify(familyMembers);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show type of disability if disability is checked
            const disabilityCheckbox = document.querySelector('input[name="with_disability"]');
            if (disabilityCheckbox) {
                document.getElementById('cause_disability_container').style.display = disabilityCheckbox.checked ? 'block' : 'none';
            }

            // Show pension type if pensioner is yes
            const pensionerYes = document.getElementById('pensioner_yes').checked;
            document.getElementById('pension_type_container').style.display = pensionerYes ? 'block' : 'none';

            // Handle assistive device visibility
            const hasDevice = document.querySelector('input[name="with_assistive_device"]:checked');
            document.getElementById('specify_device_container').style.display = (hasDevice && hasDevice.value === '1') ? 'block' : 'none';

            // Handle source of income visibility
            const sourceOfIncome = document.getElementById('source_of_income').value;
            document.getElementById('specify_income_container').style.display = sourceOfIncome === 'Others' ? 'block' : 'none';
        });
    </script>
</x-app-layout>

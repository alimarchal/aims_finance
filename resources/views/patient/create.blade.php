<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Issue Chit
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  p-4">
                <x-validation-errors class="mb-4"/>
                <x-success-message class="mb-4"/>
                <img src="{{\Illuminate\Support\Facades\Storage::url('patient.png')}}" alt="Patient Image" class="m-auto w-24">
                <h1 class="text-2xl text-center font-bold">OPD Patient Information</h1>
                <p class="text-bold text-center">Enter Patient Informaiton to register new patient</p>

                <form action="{{ route('patient.store') }}" method="POST" class="p-8">
                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 ">
                        <div>
                            <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                            <select name="title" id="title" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="">Select Title</option>
                                <option value="Mr." {{ old('title') === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Mrs." {{ old('title') === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Miss" {{ old('title') === 'Miss' ? 'selected' : '' }}>Miss</option>
                                <option value="Ms." {{ old('title') === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            </select>
                        </div>
                        <div>
                            <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter first name" value="{{ old('first_name') }}">
                        </div>
                        <div>
                            <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter last name" value="{{ old('last_name') }}">
                        </div>
                        <div>
                            <label for="father_husband_name" class="block text-gray-700 font-bold mb-2">Father/Husband Name</label>
                            <input type="text" name="father_husband_name" id="father_husband_name" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter father/husband name" value="{{ old('father_husband_name') }}">
                        </div>
                        <div>
                            <label for="age" class="block text-gray-700 font-bold mb-2">Age</label>
                            <input type="number" name="age" id="age" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter age" value="{{ old('age') }}">
                        </div>
                        <div>
                            <label for="years_months" class="block text-gray-700 font-bold mb-2">Years/Months</label>
                            <select name="years_months" id="years_months" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="">Select Year(s)</option>
                                <option value="Year(s)" {{ old('years_months') === 'Year(s)' ? 'selected' : '' }}>Years</option>
                                <option value="Month(s)" {{ old('years_months') === 'Month(s)' ? 'selected' : '' }}>Months</option>
                            </select>
                        </div>
                        <div>
                            <label for="dob" class="block text-gray-700 font-bold mb-2">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter date of birth" value="{{ old('dob') }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Sex</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="sex" value="1" {{ old('sex') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2">Male</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" class="form-radio" name="sex" value="0" {{ old('sex') == '0' ? 'checked' : '' }}>
                                    <span class="ml-2">Female</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="blood_group" class="block text-gray-700 font-bold mb-2">Blood Group</label>
                            <select name="blood_group" id="blood_group" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="Unknown" {{ old('blood_group') === 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                <option value="A+" {{ old('blood_group') === 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group') === 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group') === 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group') === 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ old('blood_group') === 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group') === 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ old('blood_group') === 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group') === 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                        </div>
                        <div>
                            <label for="registration_date" class="block text-gray-700 font-bold mb-2">Registration Date</label>
                            <input type="date" name="registration_date" id="registration_date" max="{{date('Y-m-d')}}" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter registration date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"  id="phone" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter phone number">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"  id="email" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter email address">
                        </div>
                        <div>
                            <label for="mobile" class="block text-gray-700 font-bold mb-2">Mobile</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" id="mobile" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter mobile number">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Email Alert</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center ml-6">
                                    <input type="checkbox" class="form-checkbox" name="email_alert" value="1" {{ old('email_alert') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2">Enable Email Alert</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="checkbox" class="form-checkbox" name="mobile_alert" value="1" {{ old('mobile_alert') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2">Enable Mobile Alert</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="cnic" class="block text-gray-700 font-bold mb-2">CNIC</label>
                            <input type="text" name="cnic" id="cnic" value="{{ old('cnic') }}"  class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter CNIC (00000-0000000-0)">
                        </div>

                        <div>
                            <label for="department_id" class="block text-gray-700 font-bold mb-2">OPD Department</label>
                            <select name="department_id" class="select2 w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::orderBy('name', 'ASC')->get() as $dept)
                                    <option value="{{$dept->id}}"  {{ old('department_id') === $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>


                    <livewire:government-details/>
                    <div class="flex items-center justify-between">
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            type="submit">
                            Create Patient & Issue Chit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{--    @section('custom_script')--}}
{{--        <script>--}}
{{--            const cnicInput = document.getElementById("cnic");--}}
{{--            cnicInput.addEventListener("input", (event) => {--}}
{{--                let cnic = event.target.value;--}}
{{--                cnic = cnic.replace(/\D/g, ""); // Remove all non-numeric characters--}}
{{--                cnic = cnic.slice(0, 13); // Trim to 13 digits--}}
{{--                cnic = cnic.replace(/(\d{5})(\d{7})(\d{1})/, "$1-$2-$3"); // Add hyphens--}}
{{--                event.target.value = cnic;--}}
{{--            });--}}

{{--            const mobileInput = document.getElementById("mobile");--}}
{{--            mobileInput.addEventListener("input", (event) => {--}}
{{--                let mobile = event.target.value;--}}
{{--                mobile = mobile.replace(/\D/g, ""); // Remove all non-numeric characters--}}
{{--                mobile = mobile.slice(0, 11); // Trim to 11 digits--}}
{{--                mobile = mobile.replace(/(\d{4})(\d{7})/, "$1-$2"); // Add hyphen--}}
{{--                event.target.value = mobile;--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endsection--}}
</x-app-layout>

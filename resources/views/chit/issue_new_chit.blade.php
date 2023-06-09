<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline">
            Issue New Chit
        </h2>

        <a href="{{ route('patient.actions', $patient->id) }}" class="float-right inline-flex items-center px-4 py-2 bg-red-800 border border-transparent
                        rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-red-900
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" \>
            Back
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden  sm:rounded-lg ">
                <x-validation-errors class="mb-4"/>
                <x-success-message class="mb-4"/>



                <form action="{{ route('patient.issue-new-chitStore', $patient->id) }}" method="POST" class="pr-8 pl-8 pb-8 pt-4">
                    @csrf

{{--                    <img src="{{\Illuminate\Support\Facades\Storage::url('patient.png')}}" alt="Patient Image" class="m-auto w-24">--}}
{{--                    <h1 class="text-2xl text-center font-bold">OPD Patient Information</h1>--}}
{{--                    <p class="text-bold text-center">Enter Patient Informaiton to register new patient</p>--}}

                    <div class="grid grid-cols-3 gap-4">
                        <div></div> <!-- Empty column for spacing -->
                        <div class="flex items-center justify-center">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url('Aimsa8.png') }}" alt="Logo" class="w-16 h-16">
                        </div>
                        <div class="flex flex-col items-end">
                            @php $patient_id = (string) $patient->id; @endphp
                            {!! DNS2D::getBarcodeSVG($patient_id, 'QRCODE',3,3) !!}
                        </div>
                    </div>
                    <h1 class="text-center text-2xl font-bold">Abbas Institute of Medical Sciences (AIMS)</h1>
                    <h2 class="text-1xl text-center font-bold">Muzaffarabad, Azad Jammu & Kashmir</h2>
                    <h2 class="text-1xl text-center font-extrabold mb-2">Serving the Humanity</h2>
                    <table class="table-auto w-full">
                        <tr class="border-none">
                            <td class="font-extrabold">Patient Name:</td>
                            <td class="">{{ $patient->first_name . ' ' . $patient->last_name }}</td>
                            <td class="font-extrabold">Age/Sex</td>
                            <td class="">{{ $patient->age . ' ' . $patient->years_months }}/{{ ($patient->sex == 1?'Male':'Female') }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" font-extrabold">Father/Husband Name:</td>
                            <td class="">{{ $patient->father_husband_name }}</td>
                            <td class=" font-extrabold">DOB:</td>
                            <td class="">
                                @if(!empty($patient->dob))
                                    {{ \Carbon\Carbon::parse($patient->dob)->format('d-M-Y') }}
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <td class="font-extrabold">Gender:</td>
                            <td class="">
                                @if($patient->sex == 1)
                                    Male
                                @else
                                    Female
                                @endif
                            </td>
                            <td class="font-extrabold">Blood Group:</td>
                            <td class="">{{$patient->blood_group}}</td>
                        </tr>
                        <tr>

                            <td class="font-extrabold">
                                CNIC:
                            </td>
                            <td class="">
                                {{$patient->cnic}}
                            </td>
                            <td class=" font-extrabold">Mobile:</td>
                            <td class="">{{$patient->mobile}}</td>
                        </tr>

                        <tr>
                            <td class=" font-extrabold">Registration Date:</td>
                            <td class="">
                                {{ \Carbon\Carbon::parse($patient->registration_date)->format('d-M-Y') }}
                            </td>
                            <td class=" font-extrabold">Issuing By:</td>
                            <td class="">
                                {{ Auth::user()->name }}
                            </td>
                        </tr>


                    </table>
                    <hr class="border-black h-2 mt-4">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                        <div>
                            <label for="ipd_opd" class="block text-gray-700 font-bold mb-2">IPD/OPD</label>
                            <select name="ipd_opd" id="ipd_opd" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="">Select Department</option>
                                <option value="1">OPD</option>
                                <option value="0">IPD</option>
                            </select>
                        </div>
                        <div>
                            <label for="department_id" class="block text-gray-700 font-bold mb-2">OPD Department</label>
                            <select name="department_id" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
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
                            class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            type="submit">
                            Issue Chit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

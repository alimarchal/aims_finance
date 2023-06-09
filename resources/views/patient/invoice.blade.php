<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight ">
            Patient Cart Invoice
            <div class="flex justify-center items-center float-right">
                <button onclick="window.print()" class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2" title="Members List">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </button>
            </div>
            <a href="{{route('labTest.create')}}" class="float-right inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                        rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" \>
                Create Lab Tests
            </a>
        </h2>


    </x-slot>

    <div class="py-2">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-4"/>
            <x-success-message class="mb-4"/>
            <div class="bg-white overflow-hidden  sm:rounded-lg p-4 ">
                    <div class="text-center pb-3">
                        <img class="w-24 m-auto rounded-lg" src="{{\Illuminate\Support\Facades\Storage::url('Aimsa8.png')}}" alt="{{\Illuminate\Support\Facades\Storage::url('Aimsa8.png')}}">
                    </div>
                <h1 class="text-1xl text-black font-bold text-center">
                    PAID Invoice #: {{$patient_test_id}} <br>
                    Abbas Institute of Medical Sciences (AIMS)<br>
                    Date: {{date('d-M-Y H-i-s')}}
                </h1>


                <div class="flex justify-center mt-4">
                    <table class="table-auto w-full border-collapse border border-black ">
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Name:</td>
                            <td class="border-black border px-4 py-2">
                                <a href="{{route('patient.history',$patient->id)}}" class="hover:underline text-blue-500">{{ $patient->name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Father/Son/Do:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->father_son_do }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Sex:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->sex ? 'Male' : 'Female' }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">CNIC:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->cnic }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Mobile No:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->mobile_no }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Government/Non-Government:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->government_non_gov ? 'Government' : 'Non-Government' }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Department Name:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->department_name }}</td>
                        </tr>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 font-bold text-black">Government Card No:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->government_card_no }}</td>
                        </tr>
                    </table>

                </div>


                <br>

                <div class="overflow-x-auto ">

                    <table class="table-auto w-full border-collapse border border-black ">
                        <thead>
                        <tr class="border-black">
                            <th class="border-black border px-4 py-2 text-center">No</th>
                            <th class="border-black border px-4 py-2 text-left">Test Name</th>
                            <th class="border-black border px-4 py-2">Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $total_fee = 0; @endphp
                        @foreach($patient_test as $patient_test)
                            @php $total_fee = $total_fee + $patient_test->total_amount; @endphp
                            <tr class="border-black">
                                <td class="border-black border px-4 py-2 text-center">{{$loop->iteration}}</td>
                                <td class="border-black border px-4 py-2">{{$patient_test->lab_test->name}}</td>
                                <td class="border-black border px-4 py-2 text-center font-bold">
                                        {{number_format($patient_test->total_amount,2)}}
                                </td>

                            </tr>
                        @endforeach

                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 text-right font-bold text-2xl" colspan="2">Paid Amount:</td>
                            <td class="border-black border px-4 py-2 text-center font-bold text-2xl" colspan="2">Rs.{{number_format($total_fee,2)}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">

                        <img class="m-auto rounded-lg" style="width: 150px;" src="{{\Illuminate\Support\Facades\Storage::url('paid-5025785_1280.png')}}" alt="{{\Illuminate\Support\Facades\Storage::url('Aimsa8-removebg-preview.png')}}">
                        <p>This is a computer generated receipt and does not need signature or stamp</p>

                    </div>





                </div>

            </div>
        </div>
    </div>
</x-app-layout>

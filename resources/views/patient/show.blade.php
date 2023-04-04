<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight ">
            Patient Cart Invoice
            <a href="{{route('labTest.create')}}" class="float-right inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                        rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" \>
                Create Lab Tests
            </a>


        </h2>



    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-4"/>
            <x-success-message class="mb-4"/>
            <div class="bg-white overflow-hidden  sm:rounded-lg p-4 ">
                {{--                <div class="text-center">--}}
                {{--                    <img class="h-24 w-24 m-auto rounded-lg" src="{{\Illuminate\Support\Facades\Storage::url('Aimsa8.jpg')}}" alt="{{\Illuminate\Support\Facades\Storage::url('Aimsa8-removebg-preview.png')}}">--}}
                {{--                </div>--}}
                <h1 class="text-3xl text-black font-bold text-center">
                    Patient Test Invoice Generating <br>
                    Abbas Institute of Medical Sciences (AIMS)
                </h1>


                <div class="flex justify-center mt-4">
                    <table class="table-auto w-full border-collapse border border-black ">
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Name:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->name }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Father/Son/Do:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->father_son_do }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Sex:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->sex ? 'Male' : 'Female' }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">CNIC:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->cnic }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Mobile No:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->mobile_no }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Government/Non-Government:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->government_non_gov ? 'Government' : 'Non-Government' }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Department Name:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->department_name }}</td>
                        </tr>
                        <tr>
                            <td class="border-black border px-4 py-2 font-bold text-black">Government Card No:</td>
                            <td class="border-black border px-4 py-2">{{ $patient->government_card_no }}</td>
                        </tr>
                    </table>

                </div>

                <br>
                @if($patient->government_non_gov == 1)
                    <p class="text-center text-4xl text-red-600 mb-2 font-bold mb-0">
                        Entitled - Government Employee
                    </p>
                @endif
                <form action="{{route('patient.patient_test_invoice_generate')}}" method="post" id="payment-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4 ">
                        <div>
                            <input type="hidden" name="patient_id" value="{{$patient->id}}">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Test Name</label>
                            <select name="patient_test[]" multiple="multiple" class="js-example-basic-multiple w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                @foreach(\App\Models\LabTest::orderBy('name','ASC')->get() as $labTest)
                                    <option value="{{$labTest->id}}">{{$labTest->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @section('custom_script')
        <script>
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
            });
        </script>
    @endsection
</x-app-layout>

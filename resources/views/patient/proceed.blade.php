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
                    Patient Test Invoice <br>
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
                    <p class="text-center text-4xl text-red-600 mb-2 font-bold">
                        Entitled - Government Employee
                    </p>
                @endif

                <div class="overflow-x-auto ">
                    <table class="table-auto w-full border-collapse border border-black ">
                        <thead>
                        <tr class="border-black">
                            <th class="border-black border px-4 py-2 text-center">No</th>
                            <th class="border-black border px-4 py-2 text-left">Test Name</th>
                            <th class="border-black border px-4 py-2">Total Amount</th>
                            <th class="border-black border px-4 py-2 print:hidden">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $total_fee = 0; @endphp
                        @foreach($patient->patient_test_cart as $patient_test_card)
                            @php
                                if ($patient->government_non_gov == 1)
                                {
                                    $total_fee = $total_fee + 0.00;
                                } else {
                                    $total_fee = $total_fee + $patient_test_card->lab_test->total_fee;
                                }
                            @endphp
                            <tr class="border-black">
                                <td class="border-black border px-4 py-2 text-center">{{$loop->iteration}}</td>
                                <td class="border-black border px-4 py-2">{{$patient_test_card->lab_test->name}}</td>
                                <td class="border-black border px-4 py-2 text-center font-bold">

                                    @if($patient->government_non_gov == 1)
                                        0.00
                                    @else
                                        {{number_format($patient_test_card->lab_test->total_fee,2)}}
                                    @endif
                                </td>
                                <td class="border-black border px-4 py-2 text-center print:hidden">
                                    <form action="{{ route('patient_cart.destroy', $patient_test_card->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this lab test?')" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 text-right font-bold text-2xl" colspan="2">Total Amount:</td>
                            <td class="border-black border px-4 py-2 text-center font-bold text-2xl" colspan="2">Rs.{{number_format($total_fee,2)}}</td>
                        </tr>
                        </tbody>
                    </table>


                    <form action="{{route('patient.proceed_to_invoice',$patient->id)}}" method="post" id="payment-form">
                        @csrf
                        <div class="flex justify-end m-4">
                            <div class="flex items-center space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="terms" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                                    <span class="text-gray-700 font-medium">
                                      &nbsp;I accept the payment terms and conditions<br>
                                    </span>
                                </label>
                                <button type="submit" id="pay-now-button" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                    Pay Now
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        const paymentForm = document.querySelector('#payment-form');
                        const payNowButton = document.querySelector('#pay-now-button');

                        paymentForm.addEventListener('submit', (event) => {
                            // Disable the Pay Now button to prevent double submission
                            payNowButton.disabled = true;
                        });
                    </script>



                </div>

            </div>
        </div>
    </div>
</x-app-layout>

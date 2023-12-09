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
                        <td class=" font-extrabold">Medical Record No:</td>
                        <td class="">{{ \Carbon\Carbon::now()->format('y') . '-' .$patient->id }}</td>
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
                            Collect Report At:
                        </td>
                        <td class="">
                            {{ \Carbon\Carbon::now()->addDay(1)->format('d-M-Y') }}
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
                <hr style="border: 0.5px solid black;">
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
                            <th class="border-black border px-4 py-2 text-center">S.No</th>
                            <th class="border-black border px-4 py-2 text-left">Name</th>
{{--                            <th class="border-black border px-4 py-2 text-center">Reporting Date & Time</th>--}}
                            <th class="border-black border px-4 py-2">Amount</th>
                            <th class="border-black border px-4 py-2 print:hidden">Action</th>
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
                                    $total_fee = $total_fee + $patient_test_card->fee_type->amount;
                                }
                            @endphp
                            <tr class="border-black">
                                <td class="border-black border px-4 py-2 text-center">{{$loop->iteration}}</td>
                                <td class="border-black border px-4 py-2">{{$patient_test_card->fee_type->type}}</td>
                                <td class="border-black border px-4 py-2 text-center font-bold">
                                    @if($patient->government_non_gov == 1)
                                        0.00
                                    @else
                                        {{number_format($patient_test_card->fee_type->amount,2)}}
                                    @endif
                                </td>
                                <td class="border-black border px-4 py-2 text-center print:hidden">
                                    <form action="{{ route('patient_cart.destroy', $patient_test_card->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
{{--                                        <img src="{{ url('images/delete.png') }}" class="w-8" alt="sd">--}}
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this lab test?')" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach


{{--                        @if($patient->patient_test_cart->isEmpty())--}}

                        <form method="post" action="{{ route('patient.add-to-cart', $patient->id) }}">
                            @csrf
                            <tr class="border-black">
                                <td class="border-black border px-4 py-2 text-center"></td>
                                <td class="border-black border px-4 py-2 text-center">
                                    <select name="fee_type_id" id="select2" width="100%" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                        <option value="">Select Test / Bill Type</option>
                                        @foreach(\App\Models\FeeType::orderBy('type', 'ASC')->get() as $fee_type)
                                            <option value="{{$fee_type->id}}" {{ old('fee_type_id') === $fee_type->id ? 'selected' : '' }}>{{ $fee_type->type }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                </td>
                                <td class="border-black border px-4 py-2 text-center font-bold" colspan="3">
                                    <button type="submit" class="mx-2 inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" \>
                                        Add
                                    </button>
                                </td>
                            </tr>
                        </form>
{{--                        @endif--}}



                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 text-right font-bold text-2xl" colspan="2">Total Amount:</td>
                            <td class="border-black border px-4 py-2 text-center font-bold text-2xl" colspan="2">Rs.{{number_format($total_fee,2)}}</td>
                        </tr>
                        </tbody>
                    </table>


                    <form action="{{route('patient.proceed_to_invoice',$patient->id)}}" method="post" id="payment-form">
                        @csrf


                        @foreach($patient->patient_test_cart as $patient_test_card)

                            @if($patient_test_card->fee_type->id == 2)

                                <div class="grid grid-cols-3 md:grid-cols-3 px-4 py-4 gap-3">
                                    <div>
                                        <x-label for="unit_ward" value="Unit/Ward" :required="true" />
                                        <input type="text" required name="unit_ward" id="unit_ward" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Unit/Ward" value="{{ old('unit_ward') }}">
                                    </div>
                                    <div>
                                        <x-label for="disease" value="Disease" :required="true" />
                                        <input type="text" required name="disease" id="disease" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Disease" value="{{ old('disease') }}">
                                    </div>
                                    <div>
                                        <x-label for="category" value="Category" :required="true" />
                                        <input type="text" required name="category" id="category" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Category" value="{{ old('category') }}">
                                    </div>
                                    <div>
                                        <x-label for="nok_name" value="Next of Kin Name" :required="true" />
                                        <input type="text" required name="nok_name" id="nok_name" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Next of Kin Name" value="{{ old('nok_name') }}">
                                    </div>
                                    <div>
                                        <x-label for="relation_with_patient" value="Relation with Patient" :required="true" />
                                        <input type="text" required name="relation_with_patient" id="relation_with_patient" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Relation with Patient" value="{{ old('relation_with_patient') }}">
                                    </div>
                                    <div>
                                        <x-label for="address" value="Address" :required="true" />
                                        <input type="text" required name="address" id="address" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Address" value="{{ old('address') }}">
                                    </div>
                                    <div>
                                        <x-label for="cell_no" value="Cell No" :required="true" />
                                        <input type="text" required name="cell_no" id="cell_no" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter Cell No" value="{{ old('cell_no') }}">
                                    </div>
                                    <div>
                                        <x-label for="cnic_no" value="CNIC No" :required="true" />
                                        <input type="text" required name="cnic_no" id="cnic_no" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter CNIC No" value="{{ old('cnic_no') }}">
                                    </div>
                                    <input type="hidden" name="admission_form" value="1">
                                </div>
                            @endif

                        @endforeach




                        <div class="flex justify-end m-4">
                            <div class="flex items-center space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="terms" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
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

    @section('custom_script')
        <script>
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
                $('#select2').select2();

                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            });
        </script>
    @endsection
</x-app-layout>

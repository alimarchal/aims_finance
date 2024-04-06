<x-app-layout>
    @section('custom_header')
        <style>
            @media print {
                @page {
                    size: 5.85in 8.5in;
                    margin-top: -1.4in;
                }

                .lightgray {
                    background-color: lightgray;
                }
            }
        </style>
    @endsection
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Patient Invoice
            <div class="flex justify-center items-center float-right">
                <button onclick="window.print()" class="flex items-center text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2" title="Members List">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </button>
            </div>
        </h2>


    </x-slot>

    <div class="py-0">


        <div class="grid grid-cols-3 gap-4">
            <div></div> <!-- Empty column for spacing -->
            <div class="flex items-center justify-center">
                <img src="{{ \Illuminate\Support\Facades\Storage::url('Aimsa8 copy 2.png') }}" alt="Logo" style="width: 200px;">
            </div>
            <div class="flex flex-col items-end">
                @php $patient_id = (string)  "RS.". $total_amount . "\nInvoice #: $invoice->id"; @endphp
                {!! DNS2D::getBarcodeSVG($patient_id, 'QRCODE',3,3) !!}
            </div>
        </div>


        <table class="table-auto w-full" style="font-size: 11px;">
            <tr class="border-none">
                <td class="font-extrabold">Patient Name:</td>
                <td class="">{{ $patient->title . ' ' .$patient->first_name . ' ' . $patient->last_name }}</td>
                <td class="font-extrabold">
                    @if(!empty($patient->relationship_title))
                        {{ $patient->relationship_title }}
                    @else
                        Father / Husband
                    @endif

                </td>
                <td class="">
                    {{ $patient->father_husband_name }}
                </td>
            </tr>
            <tr>
                <td class=" font-extrabold">Medical Record No:</td>
                <td class="">{{ \Carbon\Carbon::now()->format('y') . '-' .$patient->id }}-{{ $invoice->id }}</td>
                <td class=" font-extrabold">Mobile:</td>
                <td class="">{{$patient->mobile}}</td>
            </tr>
            <tr>
                <td class=" font-extrabold">Issue Date:</td>
                <td class="">
                    {{ \Carbon\Carbon::parse($invoice->created_at)->format('d-M-Y h:i:sa') }}
                </td>
                <td class="font-extrabold">Blood Group:</td>
                <td class="">{{$patient->blood_group}}</td>
            </tr>

            <tr>
                <td class="font-extrabold">
                    Category
                </td>
                <td>
                    @if($patient->government_non_gov == 1)
                        Entitled
                    @else
                        Non-Entitled
                    @endif

                </td>
                <td class="font-extrabold">Age/Sex</td>
                <td class="">{{ $patient->age . ' ' . $patient->years_months }}/{{ ($patient->sex == 1?'Male':'Female') }}
                </td>
            </tr>

            <tr>
                <td class="font-extrabold">
                    Department
                </td>
                <td style="font-size: 13px;">
                    CRP
{{--                    @if(!empty($department))--}}
{{--                        {{ $department }} <br>--}}
{{--                    @endif--}}
                </td>

                <td class="font-extrabold">
                    Head
                </td>

                <td>
                    @if(!empty($fee_category_main))
                        {{ $fee_category_main }}
                    @endif
                </td>
            </tr>

            <tr>
                <td class=" font-extrabold">Issued By:</td>
                <td class="">
                    {{ \App\Models\User::find($invoice->user_id)->name }}
                </td>
            </tr>
            <tr style="font-size: 16px; text-align: center" class="font-extrabold">
                <td colspan="4"> آپ کا نمبر ہے ({{$chitNumber}})</td>
            </tr>

{{--            <tr>--}}
{{--                <td colspan="4" style="margin: 0px; padding: 0px; font-size: 10px; font-weight: bold; text-align: center">--}}
{{--                    نوٹ : یہ کمپیوٹر سے تیار کردہ پرچی ہے اور ہم اس پرچی کی دوسری کاپی فراہم نہیں کریں گے۔--}}
{{--                    فیس کی واپسی صرف ایک گھنٹے میں مکمن ہے۔--}}
{{--                </td>--}}
{{--            </tr>--}}
            <tr style="border-bottom: 1px solid black; margin: 0px; padding: 0px; font-size: 7px; text-align: center">
                <td colspan="4">Software Developed By SeeChange Innovative - 0300-8169924</td>
            </tr>
        </table>


        @if(empty($invoice->admission))
        <h1 style="text-align: center;font-weight: bold">Patient Invoice</h1>
        <div class="overflow-x-auto" style="font-size: 12px;">
            <table class="table-auto w-full border-collapse border border-black ">
                <thead>
                <tr class="border-black lightgray">
                    <th class="border-black border text-center">S.No</th>
                    <th class="border-black border px-2 text-left">Test Name</th>
                    <th class="border-black border">Quantity</th>
                </tr>
                </thead>
                <tbody>

                @foreach($invoice->patient_test->groupBy('fee_type_id') as $test)
                    <tr class="border-black">
                        <td class="border-black border text-center">{{ $loop->iteration }}</td>
                        <td class="border-black border px-2 text-left">
                            @if(count($test) > 1)
                                {{ $test[0]->fee_type->type }}
                            @else
                                {{ $test[0]->fee_type->type }}
                            @endif
                        </td>
                        <td class="border-black border text-center">{{ count($test) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif


            @if(!empty($invoice->admission))
                <h1 style="text-align: center;font-weight: bold" class="mt-2">Hospital Admission Slip <br> A&D No: {{ $invoice->admission->id }}</h1>


                <div class="overflow-x-auto" style="font-size: 12px;">
                    <table class="table-auto w-full border-collapse border border-black ">
                        <tbody>
                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2" width="30%">Unit/Ward</td>
                            <td class="border-black border text-center" colspan="3" width="80%">{{ $invoice->admission->unit_ward }}</td>
                        </tr>

                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Disease</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->disease }}</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Referred By</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->category }}</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-center font-extrabold px-2 py-2" colspan="4">Patient Attendant Details</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Attendant Name</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->nok_name }}</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Relation With Patient</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->relation_with_patient }}</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Address</td>
                            <td class="border-black border text-center" colspan="3">
                                {{ $invoice->admission->address }} , {{ $invoice->admission->village }}  , {{ $invoice->admission->tehsil }} , {{ $invoice->admission->district }}
                            </td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">CNIC</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->cnic_no }}</td>
                        </tr>

                        <tr class="border-black">
                            <td class="border-black border text-left px-2 py-2">Cell</td>
                            <td class="border-black border text-center" colspan="3">{{ $invoice->admission->cell_no }}</td>
                        </tr>


                        <tr class="border-black">
                            <td class="border-black border text-center">MOIC Signature</td>
                            <td class="border-black border text-center" colspan="3">
                                <br>
                                <br>
                            </td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            @endif


            <script>
                const paymentForm = document.querySelector('#payment-form');
                const payNowButton = document.querySelector('#pay-now-button');

                paymentForm.addEventListener('submit', (event) => {
                    // Disable the Pay Now button to prevent double submission
                    payNowButton.disabled = true;
                });
            </script>
        </div>


        {{--            <div class="text-center">--}}

        {{--                <img class="m-auto rounded-lg" style="width: 150px;" src="{{\Illuminate\Support\Facades\Storage::url('paid-5025785_1280.png')}}" alt="{{\Illuminate\Support\Facades\Storage::url('Aimsa8-removebg-preview.png')}}">--}}
        {{--                <p>This is a computer generated receipt and does not need signature or stamp</p>--}}

        {{--            </div>--}}

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>


    @section('custom_script')
        <script>
            // Execute this code on page load
            document.addEventListener("DOMContentLoaded", function () {
                // Store the current window height before opening the print dialog
                const initialHeight = window.innerHeight;

                // Show the print dialog when the page loads
                window.print();

                // Wait for a short period (e.g., 1 second) and then check the window height again
                setTimeout(function () {
                    const currentHeight = window.innerHeight;

                    // If the window height decreased, it indicates that the print dialog is open
                    // If the window height remains the same, it means the user pressed "Cancel"
                    if (currentHeight === initialHeight) {
                        // Redirect to the specified route
                        var flag = false;

                        {{--redirectToLink("{{ route('patient.index') }}");--}}
                                @if(1)
                            flag = true
                        @else
                            flag = false;
                        @endif

                        if (flag == true) {
                            redirectToLink("{{ route('patient.index') }}");
                        } else {
                            redirectToLink("{{ route('patient.index') }}");
                        }
                    }
                }, 1000); // Adjust the delay time as needed
            });

            // Define the redirectToLink function
            function redirectToLink(url) {
                window.location.href = url;
            }
        </script>

    @endsection
</x-app-layout>


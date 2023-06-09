<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Patient Chit
            <div class="flex justify-center items-center float-right">
                <button onclick="window.print()" class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2" title="Members List">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </button>
            </div>
        </h2>


    </x-slot>

    <div class="py-0">


        <div class="max-w-7xl mx-auto  bg:whit bg-white overflow-hidden sm:rounded-lg ">
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
            {{--            <img src="{{\Illuminate\Support\Facades\Storage::url('Aimsa8.png')}}" alt="Logo" class="w-16 h-16 m-auto">--}}
            {{--            {!! DNS2D::getBarcodeSVG('4445645656', 'QRCODE',3,3) !!}--}}
            <h1 class="text-center text-2xl font-bold">Abbas Institute of Medical Sciences (AIMS)</h1>
            <h2 class="text-1xl text-center font-bold">Muzaffarabad, Azad Jammu & Kashmir</h2>
            <h2 class="text-1xl text-center font-extrabold mb-2">Serving the Humanity</h2>
            {{--                @php--}}
            {{--                    echo DNS2D::getBarcodeSVG('4445645656', 'QRCODE',3,3);;--}}
            {{--                @endphp--}}
            <table class="table-auto w-full">
                <tr class="border-none">
                    <td class="font-extrabold">Patient Name:</td>
                    <td class="">{{ $patient->first_name . ' ' . $patient->last_name }}</td>
                    <td class="font-extrabold">Age/Sex</td>
                    <td class="">{{ $patient->age . ' ' . $patient->years_months }}/{{ ($patient->sex == 1?'Male':'Female') }}
                    </td>
                    {{--                                        <td class="border-black border">{{ \Carbon\Carbon::parse($patient->created_at)->format('d-M-y h:i:s') }}</td>--}}
                </tr>
                <tr>
                    <td class=" font-extrabold">Father/Husband Name:</td>
                    <td class="">{{ $patient->father_husband_name }}</td>
                    <td class=" font-extrabold">Issue Date:</td>
                    <td class="">{{ \Carbon\Carbon::parse($chit->issued_date)->format('d-M-y h:i:s') }}</td>
                </tr>
                <tr>
                    <td class=" font-extrabold">Mobile:</td>
                    <td class="">{{$patient->mobile}}</td>
                    <td class=" font-extrabold">Reference No:</td>
                    <td class="">C{{$chit->id .'/P' . $chit->patient_id}}-{{date('ymd')}}</td>
                </tr>


                @if($chit->amount != 0 && $chit->government_non_gov == 0)
                    <tr>
                        <td class="font-extrabold">Entitlement:</td>
                        <td>Private</td>
                        <td class=" font-extrabold">Amount Payable:</td>
                        <td class="font-extrabold">Rs. {{$chit->amount}}</td>
                    </tr>
                @endif

                @if($chit->amount == 0 && $chit->government_non_gov == 1)
                    <tr>
                        <td class="font-extrabold">Entitlement:</td>
                        <td class="">Government Servant</td>
                        <td class=" font-extrabold">Card No:</td>
                        <td class="">
                            {{$chit->government_card_no}}
                        </td>
                    </tr>
                @endif



                @if($chit->government_non_gov)
                    <tr>
                        <td class="font-extrabold">
                            Department
                        </td>
                        <td colspan="3">
                            @if(!empty($chit->government_department_id))
                                {{ \App\Models\GovernmentDepartment::find($chit->government_department_id)->name }}
                            @else
                                @if($patient->government_non_gov == 1)
                                    {{ $patient->government_department->name }}
                                @endif
                            @endif
                        </td>


                    </tr>

                    <tr>
                        <td class=" font-extrabold">Amount Payable:</td>
                        <td class=" font-extrabold"> {{ $chit->amount }}</td>
                        <td class="font-extrabold">Designation:</td>
                        <td class="">{{$chit->designation}}</td>
                    </tr>
                @endif


                <tr>
                    <td class="text-2xl font-bold">
                        @if($chit->ipd_opd == 1)
                            OPD:
                        @else
                            IPD:
                        @endif
                    </td>
                    <td class="text-2xl font-bold">

                        @if($chit->ipd_opd == 1)
                            @if(!empty($chit->department))
                                {{$chit->department->name}}
                            @else
                                Emergency
                            @endif
                        @endif

                    </td>

                    <td class=" font-extrabold">Printed By:</td>
                    <td class="font-extrabold">
                        {{ auth()->user()->name }}
                    </td>

                </tr>
            </table>
            <hr class="border-black h-2">


            @if(\Carbon\Carbon::parse($chit->issued_date)->format('d-M-y') == \Carbon\Carbon::now()->format('d-M-y'))
            @else
                <h1 class="text-3xl text-center text-gray-500" style="color: gray!important;">You have printed old chit</h1>
            @endif


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
    </div>

    @section('custom_script')
        <script>
            window.onload = function () {
                window.print();
            };
        </script>
    @endsection
</x-app-layout>

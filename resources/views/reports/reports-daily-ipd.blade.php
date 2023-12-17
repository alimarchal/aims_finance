<x-app-layout>
    @section('custom_header')
        <style>
            @media print {
                @page {
                    margin-top: -30px;
                    size: landscape;
                }
                table {
                    font-size: 12px!important;
                    width: 100%;
                    table-layout: auto;
                }
                th, td {
                    white-space: nowrap;
                }
            }
        </style>
    @endsection
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            Patients
        </h2>


        <div class="flex justify-center items-center float-right">
            <div class="flex justify-center items-center float-right">
                <button onclick="window.print()" class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2" title="Members List">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </button>
            </div>

            <a href="javascript:;" id="toggle"
               class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
               title="Members List">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="hidden md:inline-block ml-2" style="font-size: 14px;">Search Filters</span>
            </a>
        </div>


    </x-slot>


    <div class="max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8" style="display: none" id="filters">
        <div class="rounded-xl p-4 bg-white shadow-lg">
            <form action="">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="user_id" class="block text-gray-700 font-bold mb-2">User Name</label>
                        <select name="user_id" id="user_id" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="">None</option>
                            @foreach(\App\Models\User::role('Front Desk/Receptionist')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        {{--                        <input type="date" name="date" value="{{ request('filter.first_name') }}" id="date" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter name">--}}
                    </div>

                    <div>
                        <label for="date" class="block text-gray-700 font-bold mb-2">Date</label>
                        <input type="date" name="date" value="{{ request('filter.first_name') }}" id="date" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter name">
                    </div>



                    <div></div>
                    <div></div>


                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Search
                        </button>
                    </div>


                </div>


            </form>
        </div>
    </div>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-4"/>
            <x-success-message class="mb-4"/>
            <div class="bg-white overflow-hidden p-4">
                <div class="overflow-x-auto">
                        <div class="grid grid-cols-3 gap-4">
                            <div></div> <!-- Empty column for spacing -->
                            <div class="flex items-center justify-center">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url('Aimsa8 copy 2.png') }}" alt="Logo" style="width: 300px;">
                            </div>
                            <div class="flex flex-col items-end">
                                @php
                                    $date = null;
                                    if(request()->has('date')) { $date = \Carbon\Carbon::parse(request('date'))->format('d-M-Y'); }
                                    else { $date = now()->format('d-M-Y h:m:s'); }
                                    $reporting_data = (string)  "Reporting Date: $date\nAIMS, Muzaffarabad, AJK";
                                @endphp
                                {!! DNS2D::getBarcodeSVG($reporting_data, 'QRCODE',3,3) !!}
                            </div>
                        </div>

                        @if(request()->has('date'))
                            <p class="text-center font-extrabold mb-4">
                                Report as of {{ \Carbon\Carbon::parse(request('date'))->format('d-M-Y h:m:s') }}
                                <br>
                                <span>Software Developed By SeeChange Innovative - Contact No: 0300-8169924</span>
                            </p>
                        @else
                            <p class="text-center font-extrabold mb-4">
                                Report as of {{ now()->format('d-M-Y h:m:s') }}  - Daily Revenue User Wise
                                <br>
                                <span>Software Developed By SeeChange Innovative - Contact No: 0300-8169924</span>
                            </p>
                        @endif
                    <table class="table-auto w-full border-collapse border border-black">
                        <thead>
                        <tr class="border-black">
                            <th class="border-black border px-4 py-2 text-left" colspan="2"></th>
                            <th class="border-black border px-4 py-2 text-center" colspan="3">Invoices</th>
                            <th class="border-black border px-4 py-2 text-center" colspan="3">Chits</th>
                            <th class="border-black border px-4 py-2 text-center" rowspan="2">Total Revenue</th>
                            <th class="border-black border px-4 py-2 text-center hidden print:block" rowspan="2">Signature</th>
                        </tr>
                        <tr class="border-black">
                            <th class="border-black border px-4 py-2 text-left">No</th>
                            <th class="border-black border px-4 py-2 text-left">Name</th>
                            <th class="border-black border px-4 py-2 text-center">Entitled</th>
                            <th class="border-black border px-4 py-2 text-center">Non Entitled</th>
                            <th class="border-black border px-4 py-2 text-center">Revenue</th>
                            <th class="border-black border px-4 py-2 text-center">Entitled</th>
                            <th class="border-black border px-4 py-2 text-center">Non Entitled</th>
                            <th class="border-black border px-4 py-2 text-center">Revenue</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalInvoices = 0;

                            $inv_non_entitled = 0;
                            $inv_entitled = 0;
                            $chit_non_entitled = 0;
                            $chit_entitled = 0;

                            $totalChits = 0;
                            $totalRevenue = 0;
                        @endphp

                        @foreach($data as $key => $value)
                            <tr class="border-black">
                                <td class="border-black border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border-black border px-4 py-2 text-left">{{ $value['Name'] }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Invoices Entitled'],0) }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Invoices Non Entitled'],0) }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Invoices'],2) }}</td>

                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Chit Entitled'],0) }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Chit Non Entitled'],0) }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Chits'],2) }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ number_format($value['Chits']+$value['Invoices'],2)  }}</td>
                                <td class="border-black border px-4 py-2 text-center hidden print:block">&nbsp;</td>
                            </tr>
                            @php
                                $inv_non_entitled += $value['Invoices Entitled'];
                                $inv_entitled += $value['Invoices Non Entitled'];

                                $chit_entitled += $value['Chit Entitled'];
                                $chit_non_entitled += $value['Chit Non Entitled'];

                                $totalInvoices += $value['Invoices'];
                                $totalChits += $value['Chits'];
                                $totalRevenue += $value['Chits']+$value['Invoices'];
                            @endphp
                        @endforeach

                        <!-- Total row -->
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 text-right font-bold" colspan="2">Total:</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($inv_non_entitled,0) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($inv_entitled,0) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($totalInvoices,2) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($chit_entitled,0) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($chit_non_entitled,0) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($totalChits,2) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($totalRevenue,2) }}</td>
                        </tr>

                        </tbody>
                    </table>


                </div>



            </div>
        </div>
    </div>
    @section('custom_script')
        <script>
            const targetDiv = document.getElementById("filters");
            const btn = document.getElementById("toggle");
            btn.onclick = function () {
                if (targetDiv.style.display !== "none") {
                    targetDiv.style.display = "none";
                } else {
                    targetDiv.style.display = "block";
                }
            };
        </script>
    @endsection
</x-app-layout>

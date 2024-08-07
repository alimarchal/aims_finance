<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Report
            <div class="flex justify-center items-center float-right">
                <div class="flex justify-center items-center float-right">
                    <button onclick="window.print()"
                            class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
                            title="Members List">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
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
        </h2>
    </x-slot>


    <div class="max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8" style="display: none" id="filters">
        <div class="rounded-xl p-4 bg-white shadow-lg">
            <form action="">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


                    <div>
                        <label for="start_date" class="block text-gray-700 font-bold mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('filter.start_date') }}" id="start_date"
                               class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                               placeholder="Enter name">
                    </div>

                    <div>
                        <label for="end_date" class="block text-gray-700 font-bold mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('filter.end_date') }}" id="end_date"
                               class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                               placeholder="Enter name">
                    </div>


                    <div>
                        <x-label for="fee_category_id" value="Category" :required="false"/>
                        <select name="filter[fee_category_id]" id="fee_category_id" style="width: 100%"
                                class="select2 w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                                multiple>
                            <option value="">None</option>
                            @foreach(\App\Models\FeeCategory::orderBy('name', 'ASC')->get() as $aw)
                                <option
                                    value="{{ $aw->id }}">{{ $aw->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div>
                        <x-label for="status" value="Status" :required="false"/>
                        <select name="status" id="status" style="width: 100%" class="select2 w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="">None</option>
                            <option value="Normal">With Return</option>
                        </select>
                    </div>





                    <div class="flex items-center justify-between">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            Search
                        </button>
                    </div>


                </div>


            </form>
        </div>
    </div>

    <div class="py-12">

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-4"/>
            <x-success-message class="mb-4"/>
            <div class="bg-white overflow-hidden p-4">
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-3 gap-4">
                        <div></div> <!-- Empty column for spacing -->
                        <div class="flex items-center justify-center">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url('Aimsa8 copy 2.png') }}" alt="Logo"
                                 style="width: 300px;">
                        </div>
                        <div class="flex flex-col items-end">
                            @php
                                $date = null;
                                if(request()->has('start_date')) { $date = \Carbon\Carbon::parse(request('start_date'))->format('d-M-Y'); }
                                else { $date = now()->format('d-M-Y h:m:s'); }
                                $reporting_data = (string)  "Reporting Date: $date\nAIMS, Muzaffarabad, AJK\nDepartment Wise Report";
                            @endphp
                            {!! DNS2D::getBarcodeSVG($reporting_data, 'QRCODE',3,3) !!}
                        </div>
                    </div>

                    @if(request()->has('start_date'))
                        <p class="text-center font-extrabold mb-4">
                            Report from {{ \Carbon\Carbon::parse(request('start_date'))->format('d-M-Y') }}
                            to {{ \Carbon\Carbon::parse(request('end_date'))->format('d-M-Y') }} - Department Wise
                            Report
                            <br>
                            <span>Software Developed By SeeChange Innovative - Contact No: 0300-8169924</span>
                        </p>
                    @else
                        <p class="text-center font-extrabold mb-4">
                            Report as of {{ now()->format('d-M-Y h:m:s') }} - Department Wise Report
                            <br>
                            <span>Software Developed By SeeChange Innovative - Contact No: 0300-8169924</span>
                        </p>
                    @endif
                    <table class="table-auto w-full border-collapse border border-black">
                        <thead>
                        <tr class="border-black">
                            <th class="border-black border px-4 py-2">ID</th>
                            <th class="border-black border px-4 py-2">Category</th>
                            <th class="border-black border px-4 py-2">Name</th>
                            <th class="border-black border px-4 py-2">Entitled</th>
                            <th class="border-black border px-4 py-2">Non Entitled</th>
                            @if(request()->input('status') == "Normal")
                                <th class="border-black border px-4 py-2">Returned</th>
                                <th class="border-black border px-4 py-2">Σ-R</th>
                                <th class="border-black border px-4 py-2">Return Amount</th>
                            @endif
                            <th class="border-black border px-4 py-2">Revenue</th>
                            <th class="border-black border px-4 py-2">HIF</th>
                            <th class="border-black border px-4 py-2">Govt</th>

{{--                            <th class="border-black border px-4 py-2">Status</th>--}}

                        </tr>
                        </thead>
                        <tbody>
                        @php $count = 1; $non_entitled = 0; $entitled = 0; $total_revenue = 0;   $return_entitled = 0; $return_non_entitled = 0; $hif = 0; $govt = 0; $return_count = 0; $return_amount = 0; $return_total_count_grand = 0; $return_total_count_grand_full = 0; @endphp
                        @foreach($categories as $fee_category_id => $fee_types)
                            @php
                                $fee_category = \App\Models\FeeCategory::find($fee_category_id);
                                $fee_category_name = $fee_category ? $fee_category->name : '';
                            @endphp
                            @foreach($fee_types as $fee_type_id => $data)
                                @php
                                    $fee_type = \App\Models\FeeType::find($fee_type_id);
                                @endphp
                                <tr class="border-black">
                                    <td class="border-black border px-4 py-2 text-center">{{ $count }}</td>
                                    @if ($loop->first)

                                        <td class="border-black border px-4 py-2 text-center"
                                            rowspan="{{ count($fee_types) }}">{{ $fee_category_name }}</td>
                                    @endif
                                    {{--                                    <td class="border-black border px-4 py-2">{{ $fee_type->fee_category_id }}</td>--}}
                                    <td class="border-black border px-4 py-2">{{ $fee_type->type }}</td>

                                    <td class="border-black border px-4 py-2 text-center">


                                        @if($fee_type->id == 1 || $fee_type->id == 19 || $fee_type->id == 107 || $fee_type->id == 108)
                                            @if(request()->has('start_date'))
                                                <a href="{{ route('chits.issued',['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'filter[government_non_gov]' => 1, 'filter[fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Entitled'] }}
                                                </a>
                                            @else
                                                <a href="{{ route('chits.issued',['start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'filter[government_non_gov]' => 1, 'filter[fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Entitled'] }}
                                                </a>
                                            @endif

                                        @else

                                            @if(request()->has('start_date'))
                                                <a href="{{ route('invoice.issued',['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'filter[government_non_government]' => 1, 'filter[patient_test.fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Entitled'] }}
                                                </a>
                                            @else
                                                <a href="{{ route('invoice.issued',['start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'filter[government_non_government]' => 1, 'filter[patient_test.fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Entitled'] }}
                                                </a>
                                            @endif
                                        @endif




                                    </td>
                                    <td class="border-black border px-4 py-2 text-center">


                                        @if($fee_type->id == 1 || $fee_type->id == 19 || $fee_type->id == 107 || $fee_type->id == 108)
                                            @if(request()->has('start_date'))
                                                <a href="{{ route('chits.issued',['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'filter[government_non_gov]' => 0, 'filter[fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Non Entitled'] }}
                                                </a>
                                            @else
                                                <a href="{{ route('chits.issued',['start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'filter[government_non_gov]' => 0, 'filter[fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data['Non Entitled'] }}
                                                </a>
                                            @endif
                                        @else
                                                @if(request()->has('start_date'))
                                                    <a href="{{ route('invoice.issued',['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'filter[government_non_government]' => 0, 'filter[patient_test.fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                        {{ $data['Non Entitled'] }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('invoice.issued',['start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'filter[government_non_government]' => 0, 'filter[patient_test.fee_type_id]' => $data['fee_type_id']]) }}" class="text-blue-500 hover:underline">
                                                        {{ $data['Non Entitled'] }}
                                                    </a>
                                                @endif
                                        @endif


                                    </td>

                                    @if(request()->input('status') == "Normal")
                                        <td class="border-black border text-center px-4 py-2">
                                            @if(!empty($data['Returned']))
                                                @php
                                                    $return_count = $return_count + \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->count();
                                                    $return_total_count_grand = \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->count();
                                                @endphp
                                                {{ \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->count() }}
                                            @endif
                                        </td>

                                        <td class="border-black border text-center px-4 py-2">

                                            {{ ($data['Entitled'] + $data['Non Entitled'])  - $return_total_count_grand }}
{{--                                            {{ $data['Entitled'] }} +--}}
{{--                                            {{ $data['Non Entitled'] }} - {{ $return_total_count_grand }}--}}
                                        </td>

                                        <td class="border-black border text-center px-4 py-2">
                                            @if(!empty($data['Returned']))
                                                @php
                                                    $return_amount = $return_amount + \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->sum('total_amount');
                                                    $return_amount_temp = \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->sum('total_amount');

                                                @endphp
                                                {{ \App\Models\PatientTest::where('fee_type_id',$data['Returned']->id)->whereBetween('created_at',[$data['Returned Start Date'],$data['Returned End Date']])->where('total_amount','<',0)->sum('total_amount') }}
                                            @endif
                                        </td>
                                    @endif

                                    <td class="border-black border px-4 py-2 text-right">{{ number_format($data['Revenue'],2) }}</td>
                                    <td class="border-black border px-4 py-2 text-right">{{ number_format($data['HIF'],2) }}</td>
                                    <td class="border-black border px-4 py-2 text-right">{{ number_format($data['Revenue'] - $data['HIF'],2) }}</td>





{{--                                    <td class="border-black border px-4 py-2 text-right">{{ $data['Status'] }}</td>--}}
                                    @php
                                        $non_entitled += $data['Non Entitled'];
                                        $entitled += $data['Entitled'];
                                        $total_revenue += $data['Revenue'];
                                        $hif += $data['HIF'];
                                        $govt += ($data['Revenue'] - $data['HIF']);
                                        if($data['Revenue'] < 0){
                                            if ($data['Entitled'] >= 1)
                                                { $return_entitled = $return_entitled + 1;}
                                            if ($data['Non Entitled'] >= 1)
                                                {$return_non_entitled = $return_non_entitled + 1;}
                                        }
                                    @endphp
                                </tr>
                                @php $count++; @endphp
                            @endforeach
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr class="border-black">
                            <td class="border-black border px-4 py-2 text-right" colspan="3">Total</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">{{ number_format($entitled,0) }}</td>
                            <td class="border-black border px-4 py-2 text-center font-bold">
                                @if(request()->input('status') == "Normal")
                                    {{ number_format($non_entitled,0) }}
                                @else
                                    {{ number_format($non_entitled,0) }}
                                @endif

                            </td>
                            @if(request()->input('status') == "Normal")
                                <td class="border-black border px-4 py-2 text-center">{{ $return_count }}</td>
                                <td class="border-black border px-4 py-2 text-center">{{ $entitled + $non_entitled - $return_count }} </td>
                                <td class="border-black border px-4 py-2 text-center">{{ $return_amount }}</td>

{{--                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($total_revenue - (abs($return_amount)),2) }}</td>--}}
{{--                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($hif,2) }}</td>--}}
{{--                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($govt,2) }}</td>--}}
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($total_revenue ,2) }}</td>
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($hif,2) }}</td>
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($govt,2) }}</td>

                            @else
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($total_revenue - (abs($return_amount)),2) }}</td>
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($hif,2) }}</td>
                                <td class="border-black border px-4 py-2 text-right font-bold">{{ number_format($govt,2) }}</td>
                            @endif


                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @section('custom_script')

        <script>


            $(document).ready(function () {
                // Initialize Select2 on your elements
                $('.js-example-basic-multiple').select2();
                $('.select2').select2();

                // This function updates the action URL of the form or modifies/creates a hidden input to include the selected values as a single query parameter
                function updateFilterParameter() {
                    // Assuming your select element has the class 'select2', adjust if necessary
                    var selectedCategories = $('#fee_category_id').val().join(',');

                    // Check if a hidden input for 'filter[fee_category_id]' already exists, if not create one
                    var hiddenInput = $('input[type=hidden][name="filter[fee_category_id]"]');
                    if (hiddenInput.length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'filter[fee_category_id]',
                            value: selectedCategories
                        }).appendTo('form');
                    } else {
                        // If it exists, just update its value
                        hiddenInput.val(selectedCategories);
                    }
                }

                // Listen for form submission
                $('form').on('submit', function (e) {
                    // Prevent the default form submission
                    e.preventDefault();

                    // Update the filter parameter before submitting the form
                    updateFilterParameter();

                    // Submit the form
                    this.submit();
                });
            });

            $(document).on('select2:open', () => {
                // Auto-focus on the search field when select2 opens
                document.querySelector('.select2-search__field').focus();
            });


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

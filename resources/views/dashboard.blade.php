<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6 ">
                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">0</div>

                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Today Revenue
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <img src="https://cdn-icons-png.flaticon.com/512/1728/1728946.png" alt="employees on leave" class="h-12 w-12">

                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    0
                                </div>
                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Total Revenue This Month
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">

                                <img src="https://cdn-icons-png.flaticon.com/512/817/817729.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    0
                                </div>
                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Today Test Performed
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <img src="https://cdn-icons-png.flaticon.com/512/3127/3127109.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">

                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    0
                                </div>
                                <div class="mt-1 text-base font-bold text-gray-600">
                                    Monthly Test Performed
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">

                                    <img src="https://cdn-icons-png.flaticon.com/512/2906/2906361.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <br>
{{--            <div class="grid grid-cols-12 gap-6 ">--}}
{{--                <div class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white p-5">--}}
{{--                    <div class="grid grid-cols-3 gap-1">--}}
{{--                        <div class="col-span-2">--}}
{{--                            <div class="text-3xl font-bold leading-8">0</div>--}}
{{--                            <div class="mt-1 text-base  font-bold text-gray-600">--}}
{{--                                Today Revenue--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-span-1 flex items-center justify-end">--}}
{{--                            <img src="https://cdn-icons-png.flaticon.com/512/1728/1728946.png" alt="employees on leave" class="h-12 w-12">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--           </div>--}}




            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

{{--                <x-welcome />--}}
            </div>
        </div>
    </div>
    @section('custom_script')
        <script>

            var options = {
                series: [{
                    name: "Desktops",
                    data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'Product Trends by Month',
                    align: 'left'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

        </script>
    @endsection
</x-app-layout>

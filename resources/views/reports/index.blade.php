<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6 ">
                <a href="{{ route('reports.opd') }}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    OPD
                                </div>

                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Reports
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <img src="{{ url('images/opd.jpeg') }}" alt="employees on leave" class="w-18">

                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('reports.ipd') }}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    ER
                                </div>
                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Reports
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">

                                <img src="{{ url('images/emergency.png') }}" alt="legal case" class="h-16 w-16">
                            </div>
                        </div>
                    </div>
                </a>
{{--                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">--}}
{{--                    <div class="p-5">--}}
{{--                        <div class="grid grid-cols-3 gap-1">--}}
{{--                            <div class="col-span-2">--}}
{{--                                <div class="text-3xl font-bold leading-8">--}}
{{--                                    0--}}
{{--                                </div>--}}
{{--                                <div class="mt-1 text-base  font-bold text-gray-600">--}}
{{--                                    Departmental Reports--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-span-1 flex items-center justify-end">--}}
{{--                                <img src="{{ Storage::url('images/3127109.png') }}" alt="legal case" class="h-12 w-12">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--                <a href="#" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">--}}

{{--                    <div class="p-5">--}}
{{--                        <div class="grid grid-cols-3 gap-1">--}}
{{--                            <div class="col-span-2">--}}
{{--                                <div class="text-3xl font-bold leading-8">--}}
{{--                                    &nbsp;--}}
{{--                                </div>--}}
{{--                                <div class="mt-1 text-base font-bold text-gray-600">--}}
{{--                                    Misc Reports--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-span-1 flex items-center justify-end">--}}

{{--                                <img src="{{ Storage::url('images/2906361.png') }}" alt="legal case" class="h-12 w-12">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
    @section('custom_script')
    @endsection
</x-app-layout>

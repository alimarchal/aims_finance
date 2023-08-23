<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-1 lg:p-1 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <!-- resources/views/users/create.blade.php -->


                    <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                        <thead class="text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                        <tr>
                            <th class="px-1 py-3 border border-black" >
                                ID
                            </th>
                            <th class="px-1 py-3 border border-black  text-center">
                                Name
                            </th>
                            <th class="px-1 py-3 border border-black  text-center">
                                Email
                            </th>
                            <th class="px-1 py-3 border border-black  text-center">
                                Role
                            </th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach ($users as $user)
                            <tr class="bg-white  border-b dark:bg-gray-800 dark:border-black text-left">
                                <th class="border px-2 py-2  border-black font-medium text-black dark:text-white">
                                    {{ $user->name }}
                                </th>
                                <th class="border px-2 py-2 border-black font-medium text-black dark:text-white text-center">
                                    {{ $user->name }}
                                </th>
                                <th class="border px-2 py-2 border-black font-medium text-black dark:text-white text-center">
                                    {{ $user->email }}
                                </th>
                                <th class="border px-2 py-2 border-black font-medium text-black dark:text-white text-center">
                                    @foreach ($user->roles as $role)
                                        {{ $role->name }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </th>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>

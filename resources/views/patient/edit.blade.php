<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Departments
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-4"/>
            <x-success-message class="mb-4"/>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <form action="{{ route('patient.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')



                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ $patient->name }}"  class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter name">
                            @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="father_son_do" class="block text-gray-700 font-bold mb-2">Father/Son/Do</label>
                            <input type="text" name="father_son_do" value="{{ $patient->father_son_do }}"  id="father_son_do" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter father/son/do">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Sex</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="sex" value="1" @if($patient->sex == 1) checked @endif>
                                    <span class="ml-2">Male</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" class="form-radio" name="sex" value="0"  @if($patient->sex == 0) checked @endif>
                                    <span class="ml-2">Female</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="cnic" class="block text-gray-700 font-bold mb-2">CNIC</label>
                            <input type="text" name="cnic" id="cnic" value="{{ $patient->cnic }}" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter CNIC (00000-0000000-0)">
                        </div>
                        <div>
                            <label for="mobile_no" class="block text-gray-700 font-bold mb-2">Mobile No.</label>
                            <input type="text" name="mobile_no" value="{{ $patient->mobile_no }}" id="mobile_no" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Enter mobile no. (0000-0000000)">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Government/Non-Government</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="government_non_gov"  @if($patient->government_non_gov == 1) checked @endif value="1">
                                    <span class="ml-2">Government</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" class="form-radio" name="government_non_gov"  @if($patient->government_non_gov == 0) checked @endif value="0">
                                    <span class="ml-2">Non-Government</span>
                                </label>
                            </div>
                        </div>
                            <div>
                                <label for="department_name" class="block text-gray-700 font-bold mb-2">Department Name</label>
                                <input type="text" name="department_name" value="{{ $patient->department_name }}" id="department_name" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Government department name">
                            </div>
                            <div>
                                <label for="government_card_no" class="block text-gray-700 font-bold mb-2">Card No</label>
                                <input type="text" name="government_card_no" value="{{ $patient->government_card_no }}" id="government_card_no" class="w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500" placeholder="Government card number">
                            </div>
                    </div>



                    <div class="flex items-center justify-between mt-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

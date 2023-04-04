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
                <form action="{{ route('patient.store') }}" method="POST">
                    @csrf
                    <livewire:government-details />

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4 mt-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-bold mb-2">Test Name</label>
                            <select name="patient_test[]" multiple="multiple" class="js-example-basic-multiple w-full px-3 py-2 border rounded-md text-gray-700 focus:outline-none focus:border-blue-500">
                                @foreach(\App\Models\LabTest::orderBy('name','ASC')->get() as $labTest)
                                    <option value="{{$labTest->id}}">{{$labTest->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Create
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>


    @section('custom_script')
        <script>
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
            });

            const cnicInput = document.getElementById("cnic");
            cnicInput.addEventListener("input", (event) => {
                let cnic = event.target.value;
                cnic = cnic.replace(/\D/g, ""); // Remove all non-numeric characters
                cnic = cnic.slice(0, 13); // Trim to 13 digits
                cnic = cnic.replace(/(\d{5})(\d{7})(\d{1})/, "$1-$2-$3"); // Add hyphens
                event.target.value = cnic;
            });

            const mobileInput = document.getElementById("mobile_no");
            mobileInput.addEventListener("input", (event) => {
                let mobile = event.target.value;
                mobile = mobile.replace(/\D/g, ""); // Remove all non-numeric characters
                mobile = mobile.slice(0, 11); // Trim to 11 digits
                mobile = mobile.replace(/(\d{4})(\d{7})/, "$1-$2"); // Add hyphen
                event.target.value = mobile;
            });
        </script>
    @endsection
</x-app-layout>

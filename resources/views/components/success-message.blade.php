<div>
    @if (session()->has('message'))
        <div class="bg-green-500 text-white font-bold py-2 px-4 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>

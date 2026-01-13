<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Record Sale for Lot #{{ $lot->lot_number }}</h2></x-slot>

    <div class="p-6">
        @if($errors->any())
            <div class="border rounded p-3 bg-gray-50 mb-4">
                <div class="font-semibold">Fix the following:</div>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.sales.store', $lot) }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm">Buyer</label>
                <select class="border rounded p-2 w-full" name="buyer_id">
                    @foreach($buyers as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm">Hammer price (£)</label>
                <input class="border rounded p-2 w-full" name="hammer_price" value="{{ old('hammer_price') }}" />
            </div>

            <button class="bg-black text-white rounded px-4 py-2">Record Sale</button>
        </form>
    </div>
</x-app-layout>

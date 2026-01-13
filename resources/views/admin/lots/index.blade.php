<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lots
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900">
                    <div class="font-semibold mb-4">Admin: Lots</div>

                    @if($lots->count() === 0)
                    <p class="text-sm text-gray-600">No lots found.</p>
                    @else
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach($lots as $lot)
                        <li>
                            <span class="font-mono">{{ $lot->lot_number }}</span>
                            — {{ $lot->artist_name }} ({{ $lot->status }})
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(method_exists($lots, 'links'))
                    <div class="mt-4">{{ $lots->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
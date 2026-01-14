<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">My Dashboard</h2>
            <a class="border rounded px-4 py-2" href="{{ route('client.preferences.edit') }}">Preferences</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-10">
        @if (session('success'))
            <div class="text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <section class="space-y-3">
            <h3 class="text-lg font-semibold">My Commission Bids</h3>

            <div class="border rounded overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Lot</th>
                            <th class="p-3 text-left">Auction</th>
                            <th class="p-3 text-left">Max Bid</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                            <tr class="border-t">
                                <td class="p-3">
                                    <a class="underline" href="{{ route('public.lots.show', $bid->lot) }}">
                                        Lot #{{ $bid->lot->lot_number }} — {{ $bid->lot->artist_name }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    {{ $bid->lot->auction?->title ?? '—' }}
                                </td>
                                <td class="p-3">£{{ number_format($bid->max_bid_amount) }}</td>
                                <td class="p-3">{{ $bid->status }}</td>
                            </tr>
                        @empty
                            <tr><td class="p-3 text-gray-500" colspan="4">No bids yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $bids->links() }}
            </div>
        </section>

        <section class="space-y-3">
            <h3 class="text-lg font-semibold">My Purchases</h3>

            <div class="border rounded overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Lot</th>
                            <th class="p-3 text-left">Hammer</th>
                            <th class="p-3 text-left">Commission</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $sale)
                            <tr class="border-t">
                                <td class="p-3">
                                    <a class="underline" href="{{ route('public.lots.show', $sale->lot) }}">
                                        Lot #{{ $sale->lot->lot_number }} — {{ $sale->lot->artist_name }}
                                    </a>
                                </td>
                                <td class="p-3">£{{ number_format((float)$sale->hammer_price, 2) }}</td>
                                <td class="p-3">£{{ number_format((float)$sale->commission_amount, 2) }}</td>
                                <td class="p-3">{{ $sale->status }}</td>
                            </tr>
                        @empty
                            <tr><td class="p-3 text-gray-500" colspan="4">No purchases yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $purchases->links() }}
            </div>
        </section>
    </div>
</x-app-layout>

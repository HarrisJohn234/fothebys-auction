<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Sales Report</h2></x-slot>

    <div class="p-6 space-y-4">
        <div class="border rounded p-4">
            <div>Total hammer: £{{ $totalHammer }}</div>
            <div>Total buyer premium: £{{ $totalPremium }}</div>
            <div class="font-semibold">Total due: £{{ $totalDue }}</div>
        </div>

        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Lot</th>
                    <th class="p-2 text-left">Auction</th>
                    <th class="p-2 text-left">Hammer</th>
                    <th class="p-2 text-left">Premium</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Sold at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $sale)
                    <tr class="border-b">
                        <td class="p-2">#{{ $sale->lot->lot_number }}</td>
                        <td class="p-2">{{ optional($sale->lot->auction)->title ?? '-' }}</td>
                        <td class="p-2">£{{ $sale->hammer_price }}</td>
                        <td class="p-2">£{{ $sale->buyer_premium_amount }}</td>
                        <td class="p-2">£{{ $sale->total_due }}</td>
                        <td class="p-2">{{ optional($sale->sold_at)->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $rows->links() }}
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Sales Report</h2></x-slot>

    <div class="p-6 space-y-4">
        <div class="border rounded p-4">
            <div>Total hammer: £{{ number_format($totalHammer, 2) }}</div>
            <div class="font-semibold">Total commission: £{{ number_format($totalCommission, 2) }}</div>
        </div>

        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Lot</th>
                    <th class="p-2 text-left">Auction</th>
                    <th class="p-2 text-left">Client</th>
                    <th class="p-2 text-left">Hammer</th>
                    <th class="p-2 text-left">Commission</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Recorded at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $sale)
                    <tr class="border-b">
                        <td class="p-2">#{{ $sale->lot->lot_number }}</td>
                        <td class="p-2">{{ optional($sale->lot->auction)->title ?? '-' }}</td>
                        <td class="p-2">{{ optional($sale->client)->email ?? '-' }}</td>
                        <td class="p-2">£{{ number_format((float) $sale->hammer_price, 2) }}</td>
                        <td class="p-2">£{{ number_format((float) $sale->commission_amount, 2) }}</td>
                        <td class="p-2">{{ $sale->status }}</td>
                        <td class="p-2">{{ optional($sale->created_at)->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $rows->links() }}
    </div>
</x-app-layout>

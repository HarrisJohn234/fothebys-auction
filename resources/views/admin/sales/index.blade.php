@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Sales</h1>
        <a class="underline" href="{{ route('admin.auctions.index') }}">Back to auctions</a>
    </div>

    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Lot #</th>
                    <th class="p-3 text-left">Artist</th>
                    <th class="p-3 text-left">Client</th>
                    <th class="p-3 text-right">Hammer</th>
                    <th class="p-3 text-right">Commission</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr class="border-t">
                        <td class="p-3 font-mono">{{ $sale->lot_number }}</td>
                        <td class="p-3">{{ $sale->artist_name }}</td>
                        <td class="p-3">{{ $sale->client_email ?? '—' }}</td>
                        <td class="p-3 text-right">
                            {{ $sale->hammer_price !== null ? '£'.number_format($sale->hammer_price, 2) : '—' }}
                        </td>
                        <td class="p-3 text-right">£{{ number_format($sale->commission_amount, 2) }}</td>
                        <td class="p-3">{{ $sale->status }}</td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="6">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection

<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Commission Bids</h2></x-slot>

    <div class="p-6">
        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Lot</th>
                    <th class="p-2 text-left">Client</th>
                    <th class="p-2 text-left">Max</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                    <tr class="border-b">
                        <td class="p-2">#{{ $bid->lot->lot_number }}</td>
                        <td class="p-2">{{ $bid->client->name }} ({{ $bid->client->email }})</td>
                        <td class="p-2">£{{ $bid->max_bid_amount }}</td>
                        <td class="p-2">{{ $bid->status }}</td>
                        <td class="p-2 flex gap-2">
                            <form method="POST" action="{{ route('admin.bids.accept', $bid) }}">
                                @csrf
                                <button class="border rounded px-2">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('admin.bids.reject', $bid) }}">
                                @csrf
                                <button class="border rounded px-2">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $bids->links() }}</div>
    </div>
</x-app-layout>

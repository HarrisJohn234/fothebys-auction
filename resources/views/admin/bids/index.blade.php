<x-app-layout>
    

    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Commission Bids</h1>
        </div>
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

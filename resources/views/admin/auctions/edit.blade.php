@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit auction</h1>

    <form method="POST" action="{{ route('admin.auctions.update', $auction) }}">
        @method('PUT')
        @include('admin.auctions._form', ['auction' => $auction, 'selectedLotIds' => $selectedLotIds])
    </form>
</div>
@endsection

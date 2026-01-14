@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit auction</h1>

    <form method="POST" action="{{ route('admin.auctions.update', $auction) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.auctions._form')
    </form>
</div>
@endsection

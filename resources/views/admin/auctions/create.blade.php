@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Create auction</h1>

    <form method="POST" action="{{ route('admin.auctions.store') }}">
        @include('admin.auctions._form')
    </form>
</div>
@endsection

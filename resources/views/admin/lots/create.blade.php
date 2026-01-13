@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Create lot</h1>

    <form method="POST" action="{{ route('admin.lots.store') }}">
        @include('admin.lots._form')
    </form>
</div>
@endsection

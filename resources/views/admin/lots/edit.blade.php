@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit lot</h1>

    <form method="POST" action="{{ route('admin.lots.update', $lot) }}">
        @method('PUT')
        @include('admin.lots._form', ['lot' => $lot])
    </form>
</div>
@endsection

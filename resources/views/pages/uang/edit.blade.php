@extends('layouts.app')
@section('title', 'Edit Transaksi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('uang.update', $transaksi) }}">
            @method('PUT')
            @include('pages.uang._form')
        </form>
    </div>
</div>
@endsection
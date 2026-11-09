@extends('layouts.app')
@section('title', 'Tambah Transaksi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('uang.store') }}">
            @include('pages.uang._form')
        </form>
    </div>
</div>
@endsection
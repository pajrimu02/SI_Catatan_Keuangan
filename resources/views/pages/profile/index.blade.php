@extends('layouts.app')

@section('title', 'Profil')

@section('content')

    <h4 class="mb-4">Profil Saya</h4>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">

                    <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" alt="Foto Profil">

                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-4">{{ $user->email }}</p>

                    <div class="text-start">
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted">
                                <i class="fa-solid fa-user text-secondary me-2"></i> Nama
                            </span>
                            <span class="fw-semibold">{{ $user->name }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted">
                                <i class="fa-solid fa-envelope text-secondary me-2"></i> Email
                            </span>
                            <span class="fw-semibold">{{ $user->email }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted">
                                <i class="fa-solid fa-calendar text-secondary me-2"></i> Bergabung Sejak
                            </span>
                            <span class="fw-semibold">{{ $user->created_at->format('d-m-Y') }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">
                                <i class="fa-solid fa-shield-halved text-secondary me-2"></i> Status Email
                            </span>
                            @if ($user->email_verified_at)
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    <i class="fa-solid fa-circle-check"></i> Terverifikasi
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="btn btn-dark w-100 mt-4">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                    </a>

                </div>
            </div>
        </div>
    </div>

@endsection
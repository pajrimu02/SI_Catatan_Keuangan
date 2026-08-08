@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('profile.index') }}" class="btn btn-light border">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="mb-0">Edit Profil</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Upload Foto Profil --}}
            <div class="text-center mb-4">
                <img src="{{ $user->avatar_url }}"
                    id="avatarPreview"
                    class="rounded-circle mb-2"
                    style="width:100px; height:100px; object-fit:cover;"
                    alt="Foto Profil">

                <div>
                    <label for="avatarInput" class="btn btn-sm btn-outline-dark">
                        <i class="fa-solid fa-camera"></i> Ganti Foto
                    </label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none"
                        onchange="previewAvatar(event)">
                </div>

                @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama</label>
                {{-- ... input nama tetap sama ... --}}
            </div>

            {{-- ... sisanya tetap sama ... --}}
        </form>
            {{-- Informasi Profil --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-user text-secondary"></i> Informasi Profil
                    </h6>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning small">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Email kamu belum diverifikasi.
                                <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0 align-baseline">
                                        Klik di sini untuk kirim ulang email verifikasi.
                                    </button>
                                </form>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="text-success mt-2">
                                        Link verifikasi baru sudah dikirim ke email kamu.
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-success small">
                                    <i class="fa-solid fa-circle-check"></i> Tersimpan.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ubah Password --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-lock text-secondary"></i> Ubah Password
                    </h6>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fa-solid fa-key"></i> Ubah Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success small">
                                    <i class="fa-solid fa-circle-check"></i> Password berhasil diubah.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i> Hapus Akun
                    </h6>

                    <p class="text-muted small mb-3">
                        Setelah akun dihapus, semua data (termasuk catatan) akan hilang secara permanen.
                        Pastikan kamu sudah yakin sebelum melanjutkan.
                    </p>

                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        <i class="fa-solid fa-trash"></i> Hapus Akun
                    </button>

                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p>Masukkan password untuk konfirmasi penghapusan akun.</p>
                                        <input type="password" name="password"
                                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                               placeholder="Password">
                                        @error('password', 'userDeletion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
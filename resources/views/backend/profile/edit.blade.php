@extends('backend.layout')

@section('title', 'Profil Saya')

@section('content')
    <div class="card" style="max-width:560px;">
        <div class="card-body">
            <h5 class="mb-3">Profil Saya</h5>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="avatar avatar-circle avatar-image" style="width:72px;height:72px;line-height:72px;">
                    <img src="{{ $admin->avatarUrl() }}" alt="{{ $admin->name }}">
                </div>
                <div>
                    <div class="fw-bold">{{ $admin->name }}</div>
                    <div class="text-muted" style="font-size:13px">{{ $admin->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" class="form-control @error('avatar') is-invalid @enderror">
                    @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 2MB.</div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection

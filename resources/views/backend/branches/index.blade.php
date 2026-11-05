@extends('backend.layout')

@section('title', 'Cabang QRIS')

@section('content')
    @if (session('reveal_key_for'))
        @php $revealed = $branches->firstWhere('id', session('reveal_key_for')); @endphp
        @if ($revealed)
            <div class="alert alert-warning">
                <strong>API Key cabang "{{ $revealed->name }}"</strong> (salin ke <code>HUBQRIS_API_KEY</code> di file <code>.env</code> cabang -- hanya ditampilkan penuh sekali):
                <div class="input-group mt-2">
                    <input type="text" id="apiKeyReveal" class="form-control" value="{{ $revealed->api_key }}" readonly onclick="this.select()">
                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('apiKeyReveal').value); this.textContent='Tersalin!'">Salin</button>
                </div>
            </div>
        @endif
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Daftar Cabang QRIS GDC Pay</h5>
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
                    <i class="feather icon-plus"></i> Tambah Cabang
                </a>
            </div>
            <p class="text-muted">Setiap apotek/situs yang mau pakai QRIS GDC Pay lewat hub ini perlu didaftarkan sebagai cabang di sini -- GDC cuma mengizinkan satu URL callback per partner, jadi hub ini yang jadi satu-satunya partner terdaftar.</p>
            <div class="table-responsive">
                <table class="table table-hover" id="branches-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>URL Callback</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr>
                                <td><b>{{ $branch->code }}</b></td>
                                <td>{{ $branch->name }}</td>
                                <td class="text-break">{{ $branch->callback_url }}</td>
                                <td>
                                    @if ($branch->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="feather icon-edit-2"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.branches.regenerate-key', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('API Key lama langsung tidak berlaku. Yakin? Pastikan siap memperbarui .env cabang segera.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="feather icon-key"></i> Buat Ulang Key
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus cabang ini? Cabang tidak akan bisa generate/cek QRIS lagi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="feather icon-trash-2"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada cabang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

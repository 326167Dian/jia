@extends('backend.layout')

@section('title', 'Detail Reseller')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">{{ $reseller->name }}</h5>
                    <div class="text-muted" style="font-size:13px">
                        {{ $reseller->username }} &middot; {{ $reseller->phone }} &middot; {{ $reseller->bank_name }} {{ $reseller->bank_account }}
                    </div>
                    @if ($reseller->address)
                        <div class="text-muted" style="font-size:13px">{{ $reseller->address }}</div>
                    @endif
                </div>
                <span class="badge bg-{{ $reseller->is_active ? 'success' : 'warning' }}">
                    {{ $reseller->is_active ? 'Aktif' : 'Menunggu Persetujuan' }}
                </span>
            </div>
            <a href="{{ route('admin.resellers.index') }}" class="btn btn-sm btn-outline-secondary mt-3">&larr; Kembali ke Daftar Reseller</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Pendaftar Lewat Referral Reseller Ini</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Paket</th>
                            <th>Status</th>
                            <th>Komisi</th>
                            <th>Bukti Komisi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->billingPeriodLabel() }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'verified' ? 'success' : ($order->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($order->commission_amount !== null)
                                        Rp {{ number_format($order->commission_amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->commission_proof_path)
                                        <a href="{{ route('media.show', $order->commission_proof_path) }}" target="_blank" rel="noopener">
                                            <img src="{{ route('media.show', $order->commission_proof_path) }}" alt="Bukti komisi" style="width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid #e2eaf3">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#commissionModal"
                                            data-action="{{ route('admin.resellers.commission', [$reseller, $order]) }}"
                                            data-name="{{ $order->name }}"
                                            onclick="openCommissionModal(this)">
                                        <i class="feather icon-dollar-sign"></i> Input Komisi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pendaftar lewat referral ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="commissionForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Input Komisi — <span id="commissionOrderName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Nominal Komisi (Rp)</label>
                            <input type="number" name="commission_amount" class="form-control" min="0" step="1000" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Bukti Transfer Komisi</label>
                            <input type="file" name="commission_proof" accept=".jpg,.jpeg,.png,.webp" class="form-control" required>
                            <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 5MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCommissionModal(el) {
        document.getElementById('commissionForm').action = el.dataset.action;
        document.getElementById('commissionOrderName').textContent = el.dataset.name;
    }
</script>
@endpush

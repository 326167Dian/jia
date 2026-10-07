@extends('backend.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted mb-1">Total Admin</div>
                    <h3 class="mb-0">{{ $stats['total_admin'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted mb-1">Total Voucher</div>
                    <h3 class="mb-0">{{ $stats['total_voucher'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted mb-1">Total Pendaftar Promo</div>
                    <h3 class="mb-0">{{ $stats['total_pendaftar'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted mb-1">Menunggu Verifikasi</div>
                    <h3 class="mb-0 text-warning">{{ $stats['menunggu_verifikasi'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Pendaftaran Promo Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bukti</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $order)
                            <tr>
                                <td>
                                    @if ($order->proof_path)
                                        <img src="{{ route('media.show', $order->proof_path) }}" alt="Bukti transfer {{ $order->order_code }}" class="proof-thumb" data-bs-toggle="modal" data-bs-target="#proofModal" onclick="document.getElementById('proofModalImg').src=this.src">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'verified' ? 'success' : ($order->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="proofModalImg" src="" alt="Bukti transfer" style="max-width:100%;border-radius:10px;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .proof-thumb{width:48px;height:48px;object-fit:cover;border-radius:8px;cursor:pointer;border:1px solid #e2eaf3;transition:transform .15s}
    .proof-thumb:hover{transform:scale(1.08);border-color:#1676db}
</style>
@endpush

@extends('backend.layout')

@section('title', 'Pendaftaran Promo')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0">Daftar Pendaftaran Promo</h5>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary {{ request('status') ? '' : 'active' }}">Semua</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-outline-warning {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'verified']) }}" class="btn btn-outline-success {{ request('status') === 'verified' ? 'active' : '' }}">Terverifikasi</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'rejected']) }}" class="btn btn-outline-danger {{ request('status') === 'rejected' ? 'active' : '' }}">Ditolak</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="orders-table">
                    <thead>
                        <tr>
                            <th>Bukti</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    @if ($order->proof_path)
                                        <img src="{{ asset('uploads/'.$order->proof_path) }}" alt="Bukti transfer {{ $order->order_code }}"
                                             class="proof-thumb"
                                             data-bs-toggle="modal" data-bs-target="#proofModal"
                                             data-title="{{ $order->order_code }} — {{ $order->name }}"
                                             data-verify-url="{{ route('admin.orders.verify', $order) }}"
                                             data-reject-url="{{ route('admin.orders.reject', $order) }}"
                                             data-pending="{{ $order->status === 'pending' ? '1' : '0' }}"
                                             onclick="openProofModal(this)">
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
                                <td class="text-end">
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('admin.orders.verify', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Verifikasi pembayaran ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                        </form>
                                        <form action="{{ route('admin.orders.reject', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pembayaran ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada pendaftaran.</td>
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
                    <h5 class="modal-title" id="proofModalTitle">Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="proofModalImg" src="" alt="Bukti transfer" style="max-width:100%;border-radius:10px;">
                </div>
                <div class="modal-footer justify-content-center" id="proofModalActions">
                    <form id="proofModalVerifyForm" method="POST" onsubmit="return confirm('Verifikasi pembayaran ini?');">
                        @csrf
                        <button type="submit" class="btn btn-success">Verifikasi</button>
                    </form>
                    <form id="proofModalRejectForm" method="POST" onsubmit="return confirm('Tolak pembayaran ini?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Tolak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<style>
    .proof-thumb{width:48px;height:48px;object-fit:cover;border-radius:8px;cursor:pointer;border:1px solid #e2eaf3;transition:transform .15s}
    .proof-thumb:hover{transform:scale(1.08);border-color:#1676db}
    #proofModalActions form{margin:0 4px}
    #orders-table_filter input{border:1px solid #d8e3f2;border-radius:8px;padding:6px 10px;margin-left:6px}
    #orders-table_length select{border:1px solid #d8e3f2;border-radius:8px;padding:4px 8px;margin:0 6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px 10px;margin-left:2px;border-radius:6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{background:var(--blue,#1676db)!important;color:#fff!important;border-color:var(--blue,#1676db)!important}
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend-assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    function openProofModal(el) {
        document.getElementById('proofModalImg').src = el.src;
        document.getElementById('proofModalTitle').textContent = el.dataset.title;

        const actions = document.getElementById('proofModalActions');
        const verifyForm = document.getElementById('proofModalVerifyForm');
        const rejectForm = document.getElementById('proofModalRejectForm');

        if (el.dataset.pending === '1') {
            actions.style.display = '';
            verifyForm.action = el.dataset.verifyUrl;
            rejectForm.action = el.dataset.rejectUrl;
        } else {
            actions.style.display = 'none';
        }
    }

    $(function () {
        $('#orders-table').DataTable({
            columnDefs: [
                { orderable: false, searchable: false, targets: [0, 7] }
            ],
            order: [[6, 'desc']],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });
    });
</script>
@endpush

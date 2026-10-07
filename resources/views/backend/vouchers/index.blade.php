@extends('backend.layout')

@section('title', 'Promo / Voucher')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Daftar Kode Voucher</h5>
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                    <i class="feather icon-plus"></i> Tambah Voucher
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="vouchers-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Potongan</th>
                            <th>Kuota</th>
                            <th>Terpakai</th>
                            <th>Berlaku s/d</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vouchers as $voucher)
                            <tr>
                                <td><b>{{ $voucher->code }}</b></td>
                                <td>
                                    @if ($voucher->type === 'percent')
                                        {{ $voucher->value }}%
                                    @else
                                        Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>{{ $voucher->quota ?? 'Tanpa batas' }}</td>
                                <td>{{ $voucher->used_count }}</td>
                                <td>{{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if (! $voucher->is_active)
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @elseif ($voucher->expires_at && $voucher->expires_at->isPast())
                                        <span class="badge bg-danger">Kedaluwarsa</span>
                                    @elseif ($voucher->quota !== null && $voucher->used_count >= $voucher->quota)
                                        <span class="badge bg-danger">Kuota Habis</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="feather icon-edit-2"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus voucher ini?');">
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
                                <td colspan="7" class="text-center text-muted">Belum ada voucher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<style>
    #vouchers-table_filter input{border:1px solid #d8e3f2;border-radius:8px;padding:6px 10px;margin-left:6px}
    #vouchers-table_length select{border:1px solid #d8e3f2;border-radius:8px;padding:4px 8px;margin:0 6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px 10px;margin-left:2px;border-radius:6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{background:var(--blue,#1676db)!important;color:#fff!important;border-color:var(--blue,#1676db)!important}
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend-assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
        $('#vouchers-table').DataTable({
            columnDefs: [{ orderable: false, searchable: false, targets: [6] }],
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

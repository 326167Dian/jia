@extends('backend.layout')

@section('title', 'Reseller')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Daftar Reseller</h5>
            <div class="table-responsive">
                <table class="table table-hover" id="resellers-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>No. Telp</th>
                            <th>Bank</th>
                            <th>Total Pendaftar</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resellers as $reseller)
                            <tr>
                                <td>{{ $reseller->name }}</td>
                                <td>{{ $reseller->username }}</td>
                                <td>{{ $reseller->phone }}</td>
                                <td>{{ $reseller->bank_name }} - {{ $reseller->bank_account }}</td>
                                <td>{{ $reseller->orders_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $reseller->is_active ? 'success' : 'warning' }}">
                                        {{ $reseller->is_active ? 'Aktif' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.resellers.show', $reseller) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="feather icon-eye"></i> Detail
                                    </a>
                                    @if (! $reseller->is_active)
                                        <form action="{{ route('admin.resellers.approve', $reseller) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui reseller ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.resellers.reject', $reseller) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan reseller ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Nonaktifkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada reseller.</td>
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
    #resellers-table_filter input{border:1px solid #d8e3f2;border-radius:8px;padding:6px 10px;margin-left:6px}
    #resellers-table_length select{border:1px solid #d8e3f2;border-radius:8px;padding:4px 8px;margin:0 6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px 10px;margin-left:2px;border-radius:6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{background:var(--blue,#1676db)!important;color:#fff!important;border-color:var(--blue,#1676db)!important}
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend-assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
        $('#resellers-table').DataTable({
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

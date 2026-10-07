@extends('backend.layout')

@section('title', 'Kelola Admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Daftar Admin</h5>
                <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                    <i class="feather icon-plus"></i> Tambah Admin
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="admins-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="feather icon-edit-2"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus admin ini?');">
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
                                <td colspan="4" class="text-center text-muted">Belum ada data admin.</td>
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
    #admins-table_filter input{border:1px solid #d8e3f2;border-radius:8px;padding:6px 10px;margin-left:6px}
    #admins-table_length select{border:1px solid #d8e3f2;border-radius:8px;padding:4px 8px;margin:0 6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px 10px;margin-left:2px;border-radius:6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{background:var(--blue,#1676db)!important;color:#fff!important;border-color:var(--blue,#1676db)!important}
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend-assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
        $('#admins-table').DataTable({
            columnDefs: [
                { orderable: false, searchable: false, targets: [3] }
            ],
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

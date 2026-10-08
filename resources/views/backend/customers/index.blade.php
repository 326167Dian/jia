@extends('backend.layout')

@section('title', 'Customer')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Data Customer</h5>
            <div class="table-responsive">
                <table class="table table-hover" id="customers-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Email</th>
                            <th>Nama Apotek</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            @php
                                $pharmacy = $customer->pharmacy;
                                $detail = [
                                    'order_code' => $customer->order_code,
                                    'name' => $customer->name,
                                    'phone' => $customer->phone,
                                    'email' => $customer->email ?: '-',
                                    'amount' => 'Rp '.number_format($customer->final_amount, 0, ',', '.'),
                                    'billing_period' => $customer->billingPeriodLabel(),
                                    'voucher_code' => $customer->voucher_code ?: '-',
                                    'status' => ucfirst($customer->status),
                                    'tanggal' => $customer->created_at->format('d/m/Y H:i'),
                                    'nama_apotek' => $pharmacy->nama_apotek ?? '-',
                                    'nomor_izin_apotek' => $pharmacy->nomor_izin_apotek ?? '-',
                                    'alamat_apotek' => $pharmacy->alamat_apotek ?? '-',
                                    'telp_apotek' => $pharmacy->telp_apotek ?? '-',
                                    'nama_apoteker' => $pharmacy->nama_apoteker ?? '-',
                                    'nomor_sipa' => $pharmacy->nomor_sipa ?? '-',
                                    'alamat_apoteker' => $pharmacy->alamat_apoteker ?? '-',
                                    'telp_apoteker' => $pharmacy->telp_apoteker ?? '-',
                                    'nama_pemilik' => $pharmacy->nama_pemilik ?? '-',
                                    'telp_pemilik' => $pharmacy->telp_pemilik ?? '-',
                                    'alamat_pemilik' => $pharmacy->alamat_pemilik ?? '-',
                                    'logo' => $pharmacy && $pharmacy->logo_path ? route('media.show', $pharmacy->logo_path) : null,
                                ];
                            @endphp
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email ?: '-' }}</td>
                                <td>{{ $pharmacy->nama_apotek ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $customer->status === 'verified' ? 'success' : ($customer->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#customerModal" data-customer='@json($detail)'>
                                        <i class="feather icon-eye"></i> Detail
                                    </button>
                                    @if ($customer->status === 'verified')
                                        @php
                                            $dataApotekUrl = route('pharmacy.edit', $customer->order_code);
                                            $waText = rawurlencode("Halo {$customer->name}, pembayaran promo MySIFA Anda sudah diverifikasi.\n\nSilakan lengkapi data apotek & buat password login member Anda di link berikut:\n{$dataApotekUrl}");
                                        @endphp
                                        <a href="https://wa.me/{{ $customer->whatsappPhone() }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                            <i class="feather icon-send"></i> Kirim Link
                                        </a>
                                    @endif
                                    @if ($pharmacy)
                                        <a href="{{ route('admin.invoice.show', $customer) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="feather icon-printer"></i> Invoice
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<style>
    #customers-table_filter input{border:1px solid #d8e3f2;border-radius:8px;padding:6px 10px;margin-left:6px}
    #customers-table_length select{border:1px solid #d8e3f2;border-radius:8px;padding:4px 8px;margin:0 6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px 10px;margin-left:2px;border-radius:6px}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{background:var(--blue,#1676db)!important;color:#fff!important;border-color:var(--blue,#1676db)!important}
    .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px 20px}
    .detail-grid h6{grid-column:1/-1;margin:14px 0 6px;color:#1676db;font-weight:800;font-size:13px;text-transform:uppercase}
    .detail-grid .row-item{display:flex;justify-content:space-between;border-bottom:1px dashed #eaf2fb;padding:6px 0;font-size:13.5px}
    .detail-grid .row-item span:first-child{color:#6c7d99}
    .detail-logo{width:72px;height:72px;object-fit:cover;border-radius:10px;border:1px solid #e2eaf3;margin-bottom:10px}
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend-assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
        $('#customers-table').DataTable({
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

    document.getElementById('customerModal').addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const d = JSON.parse(btn.getAttribute('data-customer'));

        const row = (label, value) => `<div class="row-item"><span>${label}</span><span><b>${value}</b></span></div>`;

        let html = '';
        if (d.logo) {
            html += `<img src="${d.logo}" class="detail-logo" alt="Logo apotek">`;
        }
        html += '<div class="detail-grid">';
        html += '<h6>Pendaftaran</h6>';
        html += row('Kode Pendaftaran', d.order_code);
        html += row('Status', d.status);
        html += row('Paket', d.billing_period);
        html += row('Total Bayar', d.amount);
        html += row('Kode Voucher', d.voucher_code);
        html += row('Tanggal Daftar', d.tanggal);

        html += '<h6>Kontak Customer</h6>';
        html += row('Nama', d.name);
        html += row('No. HP', d.phone);
        html += row('Email', d.email);

        html += '<h6>Data Apotek</h6>';
        html += row('Nama Apotek', d.nama_apotek);
        html += row('SIA / Sertifikat Standar', d.nomor_izin_apotek);
        html += row('Alamat Apotek', d.alamat_apotek);
        html += row('No. Telp Apotek', d.telp_apotek);

        html += '<h6>Data Apoteker</h6>';
        html += row('Nama Apoteker', d.nama_apoteker);
        html += row('SIPA', d.nomor_sipa);
        html += row('Alamat Apoteker', d.alamat_apoteker);
        html += row('No. Telp Apoteker', d.telp_apoteker);

        html += '<h6>Data Pemilik Apotek</h6>';
        html += row('Nama Pemilik', d.nama_pemilik);
        html += row('No. Telp Pemilik', d.telp_pemilik);
        html += row('Alamat Pemilik', d.alamat_pemilik);
        html += '</div>';

        document.getElementById('customerModalBody').innerHTML = html;
    });
</script>
@endpush

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_code }} - PT MYSIFA DIGITAL NETWORK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }

        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .invoice-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 20mm;
            }
        }

        .logo-badge {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 9999px;
            padding: 8px 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen flex flex-col items-center justify-start">

    <div class="no-print max-w-4xl w-full mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center space-x-2">
            <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
            <span class="text-sm font-medium text-gray-700">Preview Invoice Resmi</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                &larr; Kembali
            </a>
            <button onclick="toggleEditMode()" id="editBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span>Edit Teks</span>
            </button>
            <button onclick="window.print()" class="px-5 py-2 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <main class="invoice-card bg-white max-w-4xl w-full p-8 sm:p-12 rounded-2xl shadow-xl border border-gray-100 transition-all">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center pb-8 border-b border-gray-200 gap-6">
            <div class="flex flex-col items-start">
                <div class="logo-badge mb-3">
                    <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" alt="MySIFA" style="height:120px;width:auto;">
                </div>
            </div>

            <div class="text-left md:text-right text-xs text-gray-600 space-y-1">
                <p class="font-bold text-sm text-gray-900 uppercase tracking-wide" contenteditable="false">PT MYSIFA DIGITAL NETWORK</p>
                <p class="italic font-medium text-sky-700" contenteditable="false">(Dept. Rekayasa Perangkat Lunak)</p>
                <p class="font-semibold text-gray-700 pt-1" contenteditable="false">Head office</p>
                <p contenteditable="false" class="max-w-xs md:ml-auto">Jl. Pangrango III No. 6 Blok 6 Kayuringin Jaya</p>
                <p contenteditable="false">Bekasi Selatan-Jawa Barat</p>
            </div>
        </header>

        <section class="py-6 border-b border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Nomor Dokumentasi</span>
                <h1 class="text-lg sm:text-xl font-extrabold text-sky-600 tracking-tight" contenteditable="false">
                    INVOICE #{{ $order->order_code }}
                </h1>
                <p class="text-xs text-gray-500 mt-1" contenteditable="false">Tanggal: {{ $order->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="text-left md:text-left">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Invoiced to:</span>
                <div class="text-sm font-semibold text-gray-900 space-y-0.5">
                    <p class="text-base font-bold text-gray-800" contenteditable="false">{{ $pharmacy->nama_pemilik }}</p>
                    <p class="text-sky-700 font-medium" contenteditable="false">{{ $pharmacy->nama_apotek }}</p>
                    <p class="text-gray-600 font-normal leading-relaxed text-xs pt-1" contenteditable="false">
                        {{ $pharmacy->alamat_apotek ?: $pharmacy->alamat_pemilik ?: '-' }}<br>
                        {{ $order->phone }}
                    </p>
                </div>
            </div>
        </section>

        <section class="py-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-800 text-xs font-bold text-gray-800 uppercase tracking-wider">
                            <th class="py-3 px-2 w-2/3">Detail Pembayaran</th>
                            <th class="py-3 px-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        <tr>
                            <td class="py-4 px-2">
                                <p class="font-bold text-gray-900 text-base" contenteditable="false">{{ $setting->product_name ?? 'MySIFA E-Commerce' }}</p>
                                <p class="text-xs text-gray-500 italic mt-1" contenteditable="false">Pendaftaran {{ $order->order_code }} a.n. {{ $order->name }}</p>
                            </td>
                            <td class="py-4 px-2 text-right font-bold text-gray-800 whitespace-nowrap text-base" contenteditable="false">
                                Rp {{ number_format($order->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @if ($order->discount_amount > 0)
                            <tr>
                                <td class="py-3 px-2 text-sky-700" contenteditable="false">
                                    Diskon Voucher {{ $order->voucher_code ? '('.$order->voucher_code.')' : '' }}
                                </td>
                                <td class="py-3 px-2 text-right text-sky-700 whitespace-nowrap" contenteditable="false">
                                    - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-800">
                            <td class="py-4 px-2 font-bold text-gray-900 text-right uppercase tracking-wider text-sm">
                                Grand Total
                            </td>
                            <td class="py-4 px-2 text-right font-extrabold text-sky-600 text-lg sm:text-xl whitespace-nowrap" contenteditable="false">
                                Rp {{ number_format($order->final_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <section class="mt-8 pt-6 border-t border-gray-200 text-xs text-gray-600 space-y-2">
            <h3 class="font-bold uppercase tracking-wider text-gray-800 mb-2">Ketentuan</h3>
            <ul class="list-disc list-inside space-y-1.5 leading-relaxed font-normal">
                <li contenteditable="false">semua update fitur yang di release mysifa gratis</li>
                <li contenteditable="false">semua fitur yang diminta konsumen diadakan karena untuk menyesuaikan dengan kondisi bisnis apotek konsumen akan dikenakan biaya</li>
            </ul>
        </section>

        <section class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
            <div class="text-center">
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Verifikasi Keaslian Invoice</p>
                <div id="invoice-qr" class="inline-block p-2 bg-white border border-gray-200 rounded-lg"></div>
                <p class="text-[10px] text-gray-500 mt-2 max-w-[160px] leading-snug">Pindai kode ini untuk memastikan invoice ini tercatat resmi di sistem MySIFA</p>
            </div>
        </section>

        <footer class="mt-8 pt-6 border-t border-dashed border-gray-200 text-center text-[11px] text-gray-400">
            Terima kasih telah menggunakan layanan PT MYSIFA DIGITAL NETWORK
        </footer>
    </main>

    <script>
        new QRCode(document.getElementById('invoice-qr'), {
            text: @json(route('invoice.verify', $order->order_code)),
            width: 90,
            height: 90,
            colorDark: '#1f2937',
            colorLight: '#ffffff',
        });

        let isEditing = false;

        function toggleEditMode() {
            isEditing = !isEditing;
            const editableElements = document.querySelectorAll('[contenteditable]');
            const editBtn = document.getElementById('editBtn');

            editableElements.forEach(el => {
                el.contentEditable = isEditing ? "true" : "false";
                if (isEditing) {
                    el.classList.add('bg-amber-50', 'ring-1', 'ring-amber-300', 'rounded', 'px-1');
                } else {
                    el.classList.remove('bg-amber-50', 'ring-1', 'ring-amber-300', 'rounded', 'px-1');
                }
            });

            if (isEditing) {
                editBtn.innerHTML = `
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-emerald-700 font-semibold">Selesai Edit</span>
                `;
                editBtn.classList.add('bg-amber-100', 'border', 'border-amber-300');
            } else {
                editBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Edit Teks</span>
                `;
                editBtn.classList.remove('bg-amber-100', 'border', 'border-amber-300');
            }
        }
    </script>
</body>
</html>

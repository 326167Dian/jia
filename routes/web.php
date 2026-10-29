<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PromoOrderController as AdminPromoOrderController;
use App\Http\Controllers\Admin\ResellerController as AdminResellerController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\GdcPayWebhookController;
use App\Http\Controllers\InvoiceVerificationController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\MidtransPaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PromoOrderController;
use App\Http\Controllers\QrisPaymentController;
use App\Http\Controllers\Reseller\AuthController as ResellerAuthController;
use App\Http\Controllers\Reseller\DashboardController as ResellerDashboardController;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mysifa-ecommerce');
})->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/mobile', function () {
    return view('mobile');
});

Route::get('/landing-mysifa', function () {
    return view('landing-mysifa');
});

Route::get('/ecommerce', function () {
    return view('mysifa-ecommerce');
});

Route::post('/mobile/contact', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'string', 'max:30'],
        'message' => ['required', 'string', 'max:500'],
    ]);

    return back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
});

// Serve file upload (bukti transfer, logo apotek) lewat PHP — tidak bergantung
// pada web server mengenali file statis baru secara langsung (menghindari
// masalah cache/stat di beberapa hosting), mengikuti pola yang sudah terbukti
// jalan di project ebook.
Route::get('/media/{path}', [MediaFileController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');

// Promo checkout (public)
Route::get('/promo', [PromoOrderController::class, 'create'])->name('promo.create');
Route::post('/promo/cek-voucher', [PromoOrderController::class, 'checkVoucher'])->name('promo.check-voucher');
Route::post('/promo', [PromoOrderController::class, 'store'])->name('promo.store');
Route::get('/promo/sukses/{orderCode}', [PromoOrderController::class, 'success'])->name('promo.success');

// QRIS Dinamis (GDC Pay) & Midtrans — siap pakai begitu kredensial diisi
Route::post('/promo/qris', [QrisPaymentController::class, 'create'])->name('promo.qris.create');
Route::get('/promo/qris/{reference}', [QrisPaymentController::class, 'show'])->name('promo.qris.show');
Route::get('/promo/qris/{reference}/status', [QrisPaymentController::class, 'status'])->name('promo.qris.status');

Route::post('/promo/midtrans', [MidtransPaymentController::class, 'create'])->name('promo.midtrans.create');
Route::get('/promo/midtrans/{orderId}', [MidtransPaymentController::class, 'show'])->name('promo.midtrans.show');
Route::get('/promo/midtrans/{orderId}/status', [MidtransPaymentController::class, 'status'])->name('promo.midtrans.status');

// Webhook gateway pembayaran (dikecualikan dari CSRF, lihat bootstrap/app.php)
Route::post('/webhooks/gdcpay/qris-notification', [GdcPayWebhookController::class, 'qrisNotification'])->name('gdcpay.webhook.qris');
Route::post('/v1.0/access-token/b2b', [GdcPayWebhookController::class, 'issueAccessToken'])->name('gdcpay.webhook.token');
Route::post('/webhooks/midtrans/notification', [MidtransWebhookController::class, 'notification'])->name('midtrans.webhook.notification');

// Data apotek (diisi customer setelah pembayaran diverifikasi)
Route::get('/data-apotek/{orderCode}', [PharmacyController::class, 'edit'])->name('pharmacy.edit');
Route::post('/data-apotek/{orderCode}', [PharmacyController::class, 'update'])->name('pharmacy.update');

// Verifikasi keaslian invoice (publik, diakses lewat scan QR di invoice)
Route::get('/verifikasi-invoice/{orderCode}', [InvoiceVerificationController::class, 'show'])->name('invoice.verify');

// Member login (pelanggan yang sudah diverifikasi)
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login']);
    Route::post('/logout', [MemberAuthController::class, 'logout'])->middleware('auth:member')->name('logout');

    Route::middleware('auth:member')->group(function () {
        Route::get('/', [MemberDashboardController::class, 'index'])->name('dashboard');
    });
});

// Reseller (pendaftaran, login, dashboard referral & komisi)
Route::prefix('reseller')->name('reseller.')->group(function () {
    Route::get('/login', [ResellerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ResellerAuthController::class, 'login']);
    Route::get('/register', [ResellerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [ResellerAuthController::class, 'register'])->name('register.submit');
    Route::post('/logout', [ResellerAuthController::class, 'logout'])->middleware('auth:reseller')->name('logout');

    Route::middleware('auth:reseller')->group(function () {
        Route::get('/pending', [ResellerAuthController::class, 'showPending'])->name('pending');
        Route::get('/', [ResellerDashboardController::class, 'index'])->name('dashboard');
    });
});

// Admin backend
Route::prefix('yusuf')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:admin')->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('admins', AdminController::class)->except(['show']);

        Route::get('/pendaftaran', [AdminPromoOrderController::class, 'index'])->name('orders.index');
        Route::post('/pendaftaran/{order}/verify', [AdminPromoOrderController::class, 'verify'])->name('orders.verify');
        Route::post('/pendaftaran/{order}/reject', [AdminPromoOrderController::class, 'reject'])->name('orders.reject');

        Route::resource('promo', VoucherController::class)
            ->except(['show'])
            ->names('vouchers')
            ->parameters(['promo' => 'voucher']);

        Route::get('/pembayaran', [PaymentSettingController::class, 'edit'])->name('payment.edit');
        Route::post('/pembayaran', [PaymentSettingController::class, 'update'])->name('payment.update');

        Route::get('/customer', [CustomerController::class, 'index'])->name('customers.index');

        Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profil', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::get('/invoice/{order}', [InvoiceController::class, 'show'])->name('invoice.show');

        Route::get('/reseller', [AdminResellerController::class, 'index'])->name('resellers.index');
        Route::get('/reseller/{reseller}', [AdminResellerController::class, 'show'])->name('resellers.show');
        Route::post('/reseller/{reseller}/approve', [AdminResellerController::class, 'approve'])->name('resellers.approve');
        Route::post('/reseller/{reseller}/reject', [AdminResellerController::class, 'reject'])->name('resellers.reject');
        Route::post('/reseller/{reseller}/order/{order}/komisi', [AdminResellerController::class, 'commission'])->name('resellers.commission');
    });
});

// Link referral pendek: /{id} — simpan kode referral ke session lalu arahkan ke halaman info
// produk dulu (tombol "Ambil Promo" di sana baru lanjut ke form pembayaran).
// Diletakkan paling akhir supaya tidak menabrak path lain seperti /yusuf, /member, /reseller.
Route::get('/{reseller}', function (Reseller $reseller) {
    if ($reseller->is_active) {
        session(['referral_reseller_id' => $reseller->id]);
    }

    return redirect()->route('home');
})->name('reseller.referral');

<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinanceKomisiController;
use App\Http\Controllers\FinancePaymentController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\PartnershipReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TechnicalMeetingController;
use App\Http\Controllers\VendorApprovalController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorDocumentController;
use App\Http\Controllers\VendorRegistrationController;
use App\Models\Vendor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
// RUTE PUBLIK — REGISTRASI VENDOR (TANPA LOGIN)
// Aman: CSRF wajib, validasi ketat, status selalu 'Pending'
// =========================================================================
Route::get('/daftar-vendor', [VendorRegistrationController::class, 'show'])->name('vendor.register.show');
Route::post('/daftar-vendor', [VendorRegistrationController::class, 'store'])->name('vendor.register.store');
Route::get('/daftar-vendor/sukses', [VendorRegistrationController::class, 'success'])->name('vendor.register.success');

// =========================================================================
// RUTE PUBLIK (TIDAK PERLU LOGIN) - EVALUASI KLIEN
// =========================================================================
Route::get('/evaluasi/{token}', [ProjectController::class, 'showEvaluation'])->name('evaluation.show');
Route::post('/evaluasi/{token}', [ProjectController::class, 'submitEvaluation'])->name('evaluation.submit');

// Dashboard dibiarkan terbuka untuk semua user yang sudah login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // =========================================================================
    // RUTE UMUM / PROFIL / NOTIFIKASI
    // =========================================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Auto-read semua notifikasi saat ikon Lonceng diklik
    Route::get('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.readAll');

    // JSON API untuk realtime count vendor pending (Fase 4)
    Route::get('/api/vendor-pending-count', function () {
        return response()->json([
            'count' => Vendor::where('status_approval', 'Pending')->count(),
        ]);
    })->name('api.vendor-pending-count');

    // JSON API untuk realtime rating vendor (Fase 6)
    Route::get('/api/dashboard/vendor-ratings', function () {
        $vendors = Vendor::where('status_aktif', true)
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get(['id', 'nama_vendor', 'kategori_jasa', 'rating', 'total_review', 'status_aktif']);

        return response()->json($vendors);
    })->name('api.dashboard.vendor-ratings');

    // =========================================================================
    // RUTE SENTRALISASI MANAGEMENT EVENT (Diakses 4 Divisi)
    // =========================================================================
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::patch('/events/{id}/update-status', [EventController::class, 'updateStatus'])->name('events.updateStatus');

    // Rute Kalender Event (Khusus Strategic Partnership)
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');

    // =========================================================================
    // RUTE KHUSUS (CRUD & AKSI SPESIFIK DIVISI)
    // =========================================================================

    // 1. ADMIN CS
    Route::middleware(['role:admin_cs'])->group(function () {
        Route::resource('clients', ClientController::class)->except(['index', 'show']);

        // CS Memilih Vendor Akhir
        Route::post('/clients/{client}/pilih-vendor', [ClientController::class, 'pilihVendor'])->name('clients.pilihVendor');
    });

    // 2. STRATEGIC PARTNERSHIP
    Route::middleware(['role:partnership'])->group(function () {
        Route::resource('vendors', VendorController::class)->except(['index', 'show']);
        Route::post('/vendors/{vendor}/documents', [VendorDocumentController::class, 'store'])->name('documents.store');

        // Hak Akses Penuh Technical Meeting (Termasuk Menandai Selesai)
        Route::resource('technical-meetings', TechnicalMeetingController::class);
        Route::put('/technical-meetings/{id}/mark-as-done', [TechnicalMeetingController::class, 'markAsDone'])->name('technical-meetings.mark-done');

        Route::get('/partnership/report', [PartnershipReportController::class, 'index'])->name('partnership.report.index');
        Route::get('/partnership/report/pdf', [PartnershipReportController::class, 'downloadPdf'])->name('partnership.report.download');

        // Alur Disposisi: Divisi SP menandai vendor 'Ditinjau'
        Route::patch('/vendor-approval/{vendor}/tandai-ditinjau', [VendorApprovalController::class, 'tandaiDitinjau'])->name('vendor.approval.ditinjau');

        // Alur Disposisi: Divisi SP menyetujui berkas (Ditinjau -> Approved)
        Route::patch('/vendor-approval/{vendor}/approve-berkas', [VendorApprovalController::class, 'approveBerkas'])->name('vendor.approval.approveBerkas');

        // Alur Disposisi: Divisi SP mereset status Ditolak kembali ke Pending
        Route::patch('/vendor-approval/{vendor}/reset-pending', [VendorApprovalController::class, 'resetPending'])->name('vendor.approval.resetPending');

        // Download proposal PDF yang diupload vendor saat registrasi publik
        Route::get('/vendors/{vendor}/proposal', [VendorApprovalController::class, 'downloadProposal'])->name('vendor.proposal.download');
    });

    // 3. FINANCE (Terkait Sistem Pembayaran Independen)
    Route::middleware(['role:finance'])->group(function () {
        Route::get('/finance', [FinancePaymentController::class, 'index'])->name('finance.index');
        Route::post('/finance/{id}/invoice', [FinancePaymentController::class, 'uploadInvoice'])->name('finance.uploadInvoice');
        Route::post('/finance/{id}/validasi', [FinancePaymentController::class, 'validasiBukti'])->name('finance.validasiBukti');
        Route::post('/finance/{id}/tolak', [FinancePaymentController::class, 'tolakBukti'])->name('finance.tolakBukti');

        // Fitur Kelola Komisi Multi-Vendor (Bagian 5)
        Route::get('/finance/komisi', [FinanceKomisiController::class, 'index'])->name('finance.komisi.index');
        Route::get('/finance/komisi/{project}', [FinanceKomisiController::class, 'show'])->name('finance.komisi.show');
        Route::patch('/finance/komisi/{project}/vendor/{vendor}/tandai-lunas', [FinanceKomisiController::class, 'tandaiLunas'])->name('finance.komisi.tandaiLunas');
        Route::patch('/finance/komisi/{project}/vendor/{vendor}/nominal', [FinanceKomisiController::class, 'updateNominalKomisi'])->name('finance.komisi.nominal');
    });

    // 4. MANAGER COMMERCIAL (Terkait Validasi Dokumen Legalitas & Approval Vendor)
    Route::middleware(['role:manager_comercial'])->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::patch('/approvals/{id}', [ApprovalController::class, 'update'])->name('approvals.update');

        // Alur Approval Berjenjang: Manager Commercial approve/tolak vendor
        Route::patch('/vendor-approval/{vendor}/approve-mou', [VendorApprovalController::class, 'approveMou'])->name('vendor.approval.approveMou');
        Route::patch('/vendor-approval/{vendor}/tolak', [VendorApprovalController::class, 'tolak'])->name('vendor.approval.tolak');

        // Koreksi Rating Manual & Log Pengawasan CS
        Route::patch('/vendors/{vendor}/override-rating', [VendorController::class, 'overrideRating'])->name('vendors.overrideRating');
        Route::get('/logs', [ApprovalController::class, 'activityLogs'])->name('manager.logs');
    });

    // 5. MANAGER OPERASIONAL (Terkait Validasi Logistik & Selesainya Acara)
    Route::middleware(['role:manager_operasional'])->group(function () {
        Route::get('/operasional', [OperationalController::class, 'index'])->name('operasional.index');
        Route::patch('/operasional/{id}', [OperationalController::class, 'updateStatus'])->name('operasional.update');
        Route::get('/operasional/kinerja', [OperationalController::class, 'kinerjaCs'])->name('operasional.kinerja');

        // Trigger Finish Event & Kirim Email Evaluasi Otomatis (Rute Baru)
        Route::put('/projects/{id}/mark-completed', [ProjectController::class, 'markAsCompleted'])->name('projects.complete');

        // Aksi ACC / Tolak Vendor Klien
        Route::post('/clients/{client}/acc', [ClientController::class, 'accVendor'])->name('clients.accVendor');
        Route::post('/clients/{client}/reject', [ClientController::class, 'rejectVendor'])->name('clients.rejectVendor');
    });

    // =========================================================================
    // RUTE BERSAMA (READ-ONLY LINTAS DIVISI)
    // =========================================================================

    // Data Klien: CS (Full), Partnership (Read), Ops (Read)
    Route::middleware(['role:admin_cs|partnership|manager_operasional'])->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');

        // Rute PDF dipindahkan ke sini agar Partnership & Operasional bisa mendownloadnya
        Route::get('/clients/{id}/recommendation-pdf', [ClientController::class, 'downloadRecommendationPdf'])->name('clients.recommendation.pdf');
    });

    // Data Vendor: Partnership (Full), Ops (Read), CS (Read), Manager Commercial (Read)
    Route::middleware(['role:partnership|manager_operasional|admin_cs|manager_comercial'])->group(function () {
        Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    });

    // Detail Vendor: Partnership, Ops, dan Manager Commercial (untuk review sebelum approve MoU)
    Route::middleware(['role:partnership|manager_operasional|manager_comercial'])->group(function () {
        Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    });

    // Alur Approval Berjenjang: Diakses oleh Partnership & Manager Commercial
    Route::middleware(['role:partnership|manager_comercial'])->group(function () {
        Route::get('/vendor-approval', [VendorApprovalController::class, 'index'])->name('vendor.approval.index');
    });

});

require __DIR__.'/auth.php';

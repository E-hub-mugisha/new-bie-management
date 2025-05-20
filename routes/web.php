<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\VisitorController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', function () {
    $visitor_number = 'V123456';
    return view('welcome', compact('visitor_number'));
});

Route::get('/test-qr', function () {
    $visitor_number = 'VST20250520001';
    $qrDir = public_path('qr_codes');
    $qrPath = $qrDir . '/' . $visitor_number . '.svg';

    // Ensure the directory exists
    if (!file_exists($qrDir)) {
        mkdir($qrDir, 0755, true);
    }

    // Generate QR code if it doesn't exist
    if (!file_exists($qrPath)) {
        $qrImage = QrCode::format('svg')->size(200)->generate(route('visitor.checkin', $visitor_number));
        file_put_contents($qrPath, $qrImage);
    }

    $qrUrl = asset('qr_codes/' . $visitor_number . '.svg');

    return view('test-qr', compact('visitor_number', 'qrUrl'));
});


Route::get('/home', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about')->name('about');
});

Route::get('/contact', function () {
    return view('contact')->name('contact');
});

Route::post('/visitor/checkin', function () {
    // Logic for check-in
    return back()->with('success', 'Visitor checked in.');
})->name('visitor.checkin');

Route::post('/visitor/checkout', function () {
    // Logic for check-out
    return back()->with('success', 'Visitor checked out.');
})->name('visitor.checkout');

Route::post('/visitor/checkin/{visitor_number}', [VisitorController::class, 'checkin'])->name('visitor.checkin');
Route::post('/visitor/checkout/{visitor_number}', [VisitorController::class, 'checkout'])->name('visitor.checkout');

Route::get('/visitor/qr_checkin/{visitor_number}', [AdminController::class, 'checkInQr'])->name('visitor.qr.checkin');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/visitors', [AdminController::class, 'visitors'])->name('admin.visitors.index');
    Route::get('/admin/visitors/history', [AdminController::class, 'visitorsHistory'])->name('admin.visitor.history');
    Route::get('/admin/visitor/profile/{visitor}', [AdminController::class, 'showVisitorProfile'])->name('admin.visitor.profile');
    Route::get('/admin/visitor/history/{id}', [AdminController::class, 'AdminVisitorHistory'])->name('admin.visitor.history.show');
    Route::put('/admin/visit/{id}/checkout', [AdminController::class, 'checkoutAdmin'])->name('admin.visitors.checkout');
    Route::post('/admin/visitors/{id}', [AdminController::class, 'newVisitStore'])->name('admin.visitors.newStore');
    Route::post('/admin/visitors', [AdminController::class, 'storeAdmin'])->name('admin.visitors.store');
    Route::delete('/admin/visitor/{id}', [AdminController::class, 'destroyVisitor'])->name('admin.visitor.destroy');
    Route::post('/admin/visitors/{id}/generate-qr', [AdminController::class, 'generateQR'])->name('admin.visitors.generateQR');


    Route::get('/admin/receptionists', [ReceptionController::class, 'index'])->name('admin.reception.index');
    Route::post('/admin/add/reception', [ReceptionController::class, 'addReceptionist'])->name('admin.add.reception');
    Route::delete('/admin/reception/delete/{id}', [ReceptionController::class, 'deleteReception'])->name('admin.reception.delete');
});

Route::middleware(['auth', 'role:reception'])->group(function () {
    Route::get('/reception/dashboard', [VisitorController::class, 'dashboard'])->name('reception.dashboard.index');
    Route::get('/reception/visitors', [VisitorController::class, 'visitors'])->name('reception.visitor.index');
    Route::post('/reception/visitors', [VisitorController::class, 'store'])->name('reception.visitors.store');
    Route::put('/reception/history/{id}/checkout', [VisitorController::class, 'checkout'])->name('reception.visitors.checkout');
    Route::get('/visitor/{visitor}/profile', [VisitorController::class, 'showProfile'])->name('visitor.profile');
    Route::get('/visitors/history', [VisitorController::class, 'history'])->name('visitor.history.index');
    Route::get('/visitor/history/{id}', [VisitorController::class, 'VisitorHistory'])->name('visitor.history.show');
    Route::post('/reception/visitors/{id}', [VisitorController::class, 'newStore'])->name('reception.visitors.newStore');
    Route::get('visitor/checkin/{visitor_number}', [VisitorController::class, 'checkIn'])->name('visitor.checkin');
});

require __DIR__ . '/auth.php';

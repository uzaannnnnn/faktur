<?php

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AcceptedOrdersExport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [UserController::class, 'dashboard']);

    Route::get('/admin/faktur', [OrderController::class, 'index'])->name('faktur');
    Route::get('/admin/orders', [OrderController::class, 'allOrders'])->name('orders');
    Route::get('/export/orders/excel', function () {
        $timestamp = Carbon::now()->format('dmY_His');
        $filename = "orders-diterima-lunas_{$timestamp}.xlsx";

        return Excel::download(new AcceptedOrdersExport, $filename);
    })->name('orders.export.excel');
    Route::get('/export/orders/pdf', function () {
        $orders = \App\Models\Order::where('status', 'diterima')
            ->where('status_bayar', 'lunas')
            ->get();

        $pdf = Pdf::loadView('pdf.orders', compact('orders'));

        $timestamp = Carbon::now()->format('dmY_His');
        $filename = "orders-diterima-lunas_{$timestamp}.pdf";

        return $pdf->download($filename);
    })->name('orders.export.pdf');
    Route::get('/admin/users', [UserController::class, 'index'])->name('users');
    Route::get('/admin/add-user', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/addUser', [UserController::class, 'store'])->name('users.store');

    Route::post('/users/tambah-sales', [UserController::class, 'tambahSales'])->name('users.tambahSales');
    Route::put('/users/aktifkan/{id}', [UserController::class, 'aktifkan'])->name('users.aktifkan');
});

Route::middleware(['auth', 'role:admin,gudang'])->group(function () {
    Route::get('/gudangAdmin/obat', [ObatController::class, 'index'])->name('obats');
    Route::get('/gudangAdmin/add-obat', [ObatController::class, 'create'])->name('obats.create');
    Route::post('/gudangAdmin/addObat', [ObatController::class, 'store'])->name('obats.store');
    Route::put('/gudangAdmin/updateHarga', [ObatController::class, 'updateHarga'])->name('obats.update.harga');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'pesananSaya'])->name('pesanan.saya');
    Route::patch('/customer/{order}/terima', [CustomerController::class, 'terima'])->name('order.terima');
    Route::post('/customer/{order}/upload-bukti', [CustomerController::class, 'uploadBukti'])->name('order.upload.bukti');
    Route::post('/orders/return', [OrderController::class, 'submitReturn'])->name('orders.return');
});

Route::middleware(['auth', 'role:gudang'])->group(function () {
    Route::get('/gudang/dashboard', [OrderController::class, 'gudangIndex'])->name('gudang.orders');
    Route::post('/gudang/{order}/kirim', [OrderController::class, 'kirimOrder'])->name('gudang.kirim');
    Route::post('/gudang/returns/{id}/done', [OrderController::class, 'markReturnDone'])->name('gudang.return.done');
    Route::put('/gudang/updateObat', [ObatController::class, 'update'])->name('obats.update');
});

Route::middleware(['auth', 'role:fakturis'])->group(function () {
    Route::get('/fakturis/dashboard', [OrderController::class, 'fakturIndex'])->name('fakturis');
    Route::get('/fakturis/add-faktur', [OrderController::class, 'create'])->name('fakturis.create');
    Route::post('/fakturis/addFaktur', [OrderController::class, 'store'])->name('fakturis.store');

    Route::post('/orders/{id}/return-nota', [OrderController::class, 'generateReturnNota'])->name('orders.return.generate');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;


Route::get('/email/verify', function () {
    return view('verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/manual-verify', [VerificationController::class, 'manualVerify'])
    ->middleware('auth')
    ->name('manual.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');


Route::middleware(['web'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('show');
Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('items.like')->middleware('auth');

Route::middleware(['auth'])->prefix('mypage')->group(function () {
    Route::get('/', [MypageController::class, 'index'])->name('mypage');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/purchase/{item}/create', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::get('/purchase/confirm', [PurchaseController::class, 'confirm'])->name('purchase.confirm');
    Route::post('/purchase/payment-method', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.updatePayment');
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('/purchase/{item}/address', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/{item}/address', [AddressController::class, 'update'])->name('address.update');
});
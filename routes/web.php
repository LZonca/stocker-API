<?php

use App\Http\Middleware\web\SetLocale;
use App\Livewire\GroupProduit;
use App\Livewire\GroupStock;
use App\Livewire\GroupView;
use App\Livewire\Groups;
use App\Livewire\ListsView;
use App\Livewire\ListView;
use App\Livewire\LogsView;
use App\Livewire\ProductView;
use App\Livewire\Stocks;
use App\Livewire\StockView;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/download-mobile', function () {
    $version = config('app.version');
    $apkFile = "public/apps/mobile/stocker-release-{$version}.apk";
    $apkUrl = Storage::url($apkFile);
    return redirect($apkUrl);
})->name('download-mobile');

Route::localizedGroup(function () {

    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/mobile-app/{any?}', function () {
        return view('mobile-app.index');
    })->where('any', '^(?!.*\.(js|css|png|jpg|jpeg|svg|json)).*$')->name('mobile-app');
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');


        Route::get('/groups', Groups::class)->name('groups');
        Route::get('/groups/{groupe}/stocks/{stock}', GroupStock::class)->name('groups.stocks-stock');
        Route::get('/groups/{groupe}/stocks/{stock}/products/{product}', GroupProduit::class)->name('groups.stocks-stock.products-product');

        Route::get('/stocks', Stocks::class)->name('stocks');
        Route::get('/stocks/{stock}', StockView::class)->name('stock.view');
        Route::get('/groups/{group}', GroupView::class)->name('group.view');
        Route::get('/stocks/{stock}/products/{product}', ProductView::class)->name('product.view');

        Route::get('/lists', ListsView::class)->name('lists.index');
        Route::get('/lists/{list}', ListView::class)->name('lists.show');
        Route::get('/logs', LogsView::class)->name('logs.index');
    });
});

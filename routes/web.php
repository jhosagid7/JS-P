<?php

use App\Http\Livewire\Dash;
use App\Http\Livewire\Select2;
use App\Http\Livewire\Component1;
use App\Http\Livewire\PosController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CoinsController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\CashoutController;
use App\Http\Livewire\ReportsController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\ProductsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PrinterController;
use App\Http\Livewire\CategoriesController;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('auth.login');
});

//Auth::routes();

Auth::routes(['register' => false]); // deshabilitamos el registro de nuevos users

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', Dash::class);


Route::middleware(['auth'])->group(function () {

    Route::get('categories', CategoriesController::class)->middleware('role:Admin');
    Route::get('products', ProductsController::class);
    Route::get('coins', CoinsController::class);
    Route::get('pos', PosController::class);


    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('roles', RolesController::class);
        Route::get('permisos', PermisosController::class);
        Route::get('asignar', AsignarController::class);
    });

    Route::get('users', UsersController::class);
    Route::get('cashout', CashoutController::class);
    Route::get('reports', ReportsController::class);
    Route::get('dash', Dash::class)->name('dash');

    //reportes PDF
    Route::get('report/pdf/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reportPDF']);
    Route::get('report/pdf/{user}/{type}', [ExportController::class, 'reportPDF']);


    //reportes EXCEL
    Route::get('report/excel/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reporteExcel']);
    Route::get('report/excel/{user}/{type}', [ExportController::class, 'reporteExcel']);

    //Cashout PDF
    Route::get('cashout/pdf/{user}/{f1}/{f2}', [ExportController::class, 'cashoutPDF']);

    // //Creamos rutas para la Impresion de tickets
    // Route::get('print/sale/{$id}', [PrinterController::class, 'ticketSale']);

    // Route::get('print/last-sale/{$id}', [PrinterController::class, 'ticketLastSale']);
});























Route::get('conte', Component1::class);
Route::get('conte2', function () {
    return view('contenedor');
});



//rutas utils
Route::get('select2', Select2::class);

<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\{
    DataController,
    PageSectionOrderController
};


Route::get('/', [DataController::class, 'index'])->name('home');

Route::get('/{slug}.html', [DataController::class, 'makePage'])
    ->where('slug', '[A-Za-z-]+')
    ->name('page');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    
    Route::get('page-sections/order/{page_id?}', [
        PageSectionOrderController::class, 'order'
    ])->name('voyager.page-sections.order');
});

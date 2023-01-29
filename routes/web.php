<?php

use Zdslab\Laravelinit\Controllers\{
    DataController,
    PageSectionOrderController,
};
use Illuminate\Support\Facades\Route;

Route::get('/index', [
    DataController::class, 'index'
])->name('home');

Route::get('/{slug}.html', [
    DataController::class, 'makePage'
])->where('slug', '[A-Za-z-]+')->name('page');

Route::get('admin/page-sections/order/{page_id?}', [PageSectionOrderController::class, 'order'])->name('voyager.page-sections.order');

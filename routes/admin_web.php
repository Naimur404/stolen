<?php

use Illuminate\Support\Facades\Route;



Route::prefix('admin')->group(function () {
});

// Route::prefix('color-version')->group(function () {
// });

// Route::prefix('page-layout')->group(function () {
// });

// Route::prefix('footers')->group(function () {

// });

Route::prefix('starter-kit')->group(function () {
	Route::view('layout-dark', 'admin.color-version.layout-dark')->name('layout-dark');
	Route::view('boxed', 'admin.page-layout.boxed')->name('boxed');
	Route::view('layout-rtl', 'admin.page-layout.layout-rtl')->name('layout-rtl');
	Route::view('footer-light', 'admin.footers.footer-light')->name('footer-light');
	Route::view('footer-dark', 'admin.footers.footer-dark')->name('footer-dark');
	Route::view('footer-fixed', 'admin.footers.footer-fixed')->name('footer-fixed');

	Route::view('default-layout', 'multiple.default-layout')->name('default-layout');

    Route::view('datatable-AJAX', 'admin.tables.datatable-AJAX')->name('datatable-AJAX');
    Route::view('base-input', 'admin.forms.base-input')->name('form');
    Route::view('role', 'admin.role.role')->name('role');
    Route::view('user', 'admin.role.user_role')->name('user');
});



<?php

use App\Http\Controllers\Permission;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Role;
use App\Http\Controllers\UserRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::view('/dashboard', 'admin.color-version.index')->middleware(['auth'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('layout-dark', 'admin.color-version.layout-dark')->name('layout-dark');
	Route::view('boxed', 'admin.page-layout.boxed')->name('boxed');
	Route::view('layout-rtl', 'admin.page-layout.layout-rtl')->name('layout-rtl');
	Route::view('footer-light', 'admin.footers.footer-light')->name('footer-light');
	Route::view('footer-dark', 'admin.footers.footer-dark')->name('footer-dark');
	Route::view('footer-fixed', 'admin.footers.footer-fixed')->name('footer-fixed');

	Route::view('default-layout', 'multiple.default-layout')->name('default-layout');


    Route::view('/datatable-AJAX', 'admin.tables.datatable-AJAX')->name('datatable-AJAX');
    Route::view('/base-input', 'admin.forms.base-input')->name('form');
    Route::view('/role', 'admin.role.role')->name('role');
    Route::get('/user', [UserRole::class,'users'])->name('user');
    Route::get('/add_user', [UserRole::class,'addUsers'])->name('add_user');
    Route::post('/add_user_store', [UserRole::class,'addUsersStore'])->name('add_user_store');

//permission route

    Route::get('/permission', [Permission::class,'permission'])->name('permission');
    Route::get('/add_permission', [Permission::class,'addPermission'])->name('add_permission');
    Route::post('/store_permission', [Permission::class,'storePermission'])->name('store_permission');
    Route::post('/update_permission', [Permission::class,'updatePermission'])->name('update_permission');
    Route::get('/edit_permission/{id}', [Permission::class,'editPermission'])->name('edit_permission');
    Route::get('/delete_permission/{id}', [Permission::class,'deletePermission'])->name('delete_permission');

    //role route

    Route::get('/role', [Role::class,'role'])->name('role');
    Route::get('/add_role', [Role::class,'addRole'])->name('add_role');
    Route::post('/store_role', [Role::class,'storeRole'])->name('store_role');
    Route::post('/update_role', [Role::class,'updateRole'])->name('update_role');
    Route::get('/edit_role/{id}', [Role::class,'editRole'])->name('edit_role');
    Route::get('/delete_role/{id}', [Role::class,'deleteRole'])->name('delete_role');

    Route::post('/get_role', [Role::class,'getRole'])->name('get_role');

    //add role in permission

    Route::get('/add/role/permission', [Role::class,'addRolePermission'])->name('rolepermission');
    Route::post('/store/role/permission', [Role::class,'storeRolePermission'])->name('add_role_permission');
    Route::get('/all/role/permission', [Role::class,'allRolePermission'])->name('allrolepermission');
    Route::get('/edit/role/permission/{id}', [Role::class,'editRolePermission'])->name('editrolepermission');
});

require __DIR__.'/auth.php';

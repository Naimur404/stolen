<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserRoleController;
use Database\Seeders\PaymentMethodSeeder;
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
require __DIR__.'/auth.php';
Route::get('/', [SettingController::class, 'index']);

Route::get('/dashboard', [DashBoardController::class,'index'])->middleware(['auth'])->name('index');

Route::group(['middleware' => ['auth'], 'prefix' => 'administrativearea'],function () {
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
    Route::get('/user', [UserRoleController::class,'users'])->name('user');
    Route::get('/add_user', [UserRoleController::class,'addUsers'])->name('add_user');
    Route::post('/add_user_store', [UserRoleController::class,'addUsersStore'])->name('add_user_store');
    Route::get('/delete_user/{id}', [UserRoleController::class,'deleteUser'])->name('delete_user');
    Route::get('/edit_user/{id}', [UserRoleController::class,'editUser'])->name('edit_user');
    Route::post('/update/user', [UserRoleController::class,'updateUser'])->name('updateuser');
    Route::get('/add/user/org/{id}', [UserRoleController::class,'addUserOrg'])->name('adduserorg');

    Route::post('/store/user/org', [UserRoleController::class,'storeUserOrg'])->name('storeuserorg');

//permission route

    Route::get('/permission', [PermissionController::class,'permission'])->name('permission');
    Route::get('/add_permission', [PermissionController::class,'addPermission'])->name('add_permission');
    Route::post('/store_permission', [PermissionController::class,'storePermission'])->name('store_permission');
    Route::post('/update_permission', [PermissionController::class,'updatePermission'])->name('update_permission');
    Route::get('/edit_permission/{id}', [PermissionController::class,'editPermission'])->name('edit_permission');
    Route::get('/delete_permission/{id}', [PermissionController::class,'deletePermission'])->name('delete_permission');

    //role route

    Route::get('/role', [RoleController::class,'role'])->name('role');
    Route::get('/add_role', [RoleController::class,'addRole'])->name('add_role');
    Route::post('/store_role', [RoleController::class,'storeRole'])->name('store_role');
    Route::post('/update_role', [RoleController::class,'updateRole'])->name('update_role');
    Route::get('/edit_role/{id}', [RoleController::class,'editRole'])->name('edit_role');
    Route::get('/delete_role/{id}', [RoleController::class,'deleteRole'])->name('delete_role');

    Route::post('/get_role', [RoleController::class,'getRole'])->name('get_role');

    //add role in permission

    Route::get('/add/role/permission', [RoleController::class,'addRolePermission'])->name('rolepermission');
    Route::post('/store/role/permission', [RoleController::class,'storeRolePermission'])->name('add_role_permission');
    Route::get('/all/role/permission', [RoleController::class,'allRolePermission'])->name('allrolepermission');
    Route::get('/edit/role/permission/{id}', [RoleController::class,'editRolePermission'])->name('editrolepermission');
    Route::post('/update/role/permission/{id}', [RoleController::class,'updateRolePermission'])->name('update_role_permission');
    Route::get('/delete/role/permission/{id}', [RoleController::class,'deleteRolePermission'])->name('deleterolepermission');

    //setting route

    Route::get('/site/setting', [SettingController::class,'setting'])->name('setting');
    Route::post('/update/setting', [SettingController::class,'updateSetting'])->name('updatesetting');


});

Route::group(['middleware' => ['auth']],function () {
    //route for HealthOrganization

Route::resource('category',CategoryController::class);

Route::resource('unit',UnitController::class);
Route::resource('payment-method',PaymentMethodController::class);

});
// php


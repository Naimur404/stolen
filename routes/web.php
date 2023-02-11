<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineDistributeController;
use App\Http\Controllers\MedicinePurchaseController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\OutletStockController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\SupplierController;

use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseReturnController;
use App\Http\Controllers\WarehouseStockController;
use App\Models\CustomerManagement;
use App\Models\MedicineDistribute;
use App\Models\OutletStock;
use App\Models\StockRequest;
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

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'],function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/role', 'admin.role.role')->name('role');
    //user route

    Route::get('/user', [UserRoleController::class,'users'])->name('user');
    Route::get('/add_user', [UserRoleController::class,'addUsers'])->name('add_user');
    Route::post('/add-user-store', [UserRoleController::class,'addUsersStore'])->name('add_user_store');
    Route::get('/delete-user/{id}', [UserRoleController::class,'deleteUser'])->name('delete_user');
    Route::get('/edit-user/{id}', [UserRoleController::class,'editUser'])->name('edit_user');
    Route::post('/update/user', [UserRoleController::class,'updateUser'])->name('updateuser');
    Route::get('/add/user/org/{id}', [UserRoleController::class,'addUserOrg'])->name('adduserorg');
    Route::post('/store/user/org', [UserRoleController::class,'storeUserOrg'])->name('storeuserorg');


//permission route

    Route::get('/permission', [PermissionController::class,'permission'])->name('permission');
    Route::get('/add-permission', [PermissionController::class,'addPermission'])->name('add_permission');
    Route::post('/store-permission', [PermissionController::class,'storePermission'])->name('store_permission');
    Route::post('/update-permission', [PermissionController::class,'updatePermission'])->name('update_permission');
    Route::get('/edit-permission/{id}', [PermissionController::class,'editPermission'])->name('edit_permission');
    Route::get('/delete-permission/{id}', [PermissionController::class,'deletePermission'])->name('delete_permission');

    //role route

    Route::get('/role', [RoleController::class,'role'])->name('role');
    Route::get('/add-role', [RoleController::class,'addRole'])->name('add_role');
    Route::post('/store-role', [RoleController::class,'storeRole'])->name('store_role');
    Route::post('/update-role', [RoleController::class,'updateRole'])->name('update_role');
    Route::get('/edit-role/{id}', [RoleController::class,'editRole'])->name('edit_role');
    Route::get('/delete-role/{id}', [RoleController::class,'deleteRole'])->name('delete_role');

    Route::post('/get-role', [RoleController::class,'getRole'])->name('get_role');

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
//resource route
Route::resource('payment-method',PaymentMethodController::class);
Route::resource('supplier',SupplierController::class);
Route::resource('warehouse',WarehouseController::class);
Route::resource('outlet',OutletController::class);
Route::resource('medicine-purchase',MedicinePurchaseController::class);
Route::resource('warehouse-stock',WarehouseStockController::class);
Route::resource('outlet-stock',OutletStockController::class);
Route::resource('distribute-medicine',MedicineDistributeController::class);
Route::resource('warehouse-return',WarehouseReturnController::class);
Route::resource('customer',CustomerManagementController::class);
Route::resource('stock-request',StockRequestController::class);

//checkin route
Route::get('medicine-purchase/{id}/check-in',[MedicinePurchaseController::class,'checkIn'])->name('medicine-purchase.checkIn');
Route::get('distribute-medicine/{id}/check-in',[MedicineDistributeController::class,'checkIn'])->name('distribute-medicine.checkIn');



//active status route
Route::get('/delete/{medicineid}/{distributeid}', [MedicineDistributeController::class,'medicineDistributeDetailDelete'])->name('delete.medicineDistributeDetailDelete');
Route::get('/status-supplier/{id}/{status}', [SupplierController::class,'active'])->name('supplier.active');
Route::get('/status-warehouse/{id}/{status}', [WarehouseController::class,'active'])->name('warehouse.active');
Route::get('/status-outlet/{id}/{status}', [OutletController::class,'active'])->name('outlet.active');
Route::get('/status-customer/{id}/{status}', [CustomerManagementController::class,'active'])->name('customer.active');

Route::get('/has-sent/{id}/{status}', [StockRequestController::class,'hasSent'])->name('hasSent');
Route::Post('/has_accepted', [StockRequestController::class,'hasAccepted'])->name('hasAccepted');
Route::get('/has_accepted/{id}/{status}/{medicineid}/medicine', [StockRequestController::class,'hasAcceptedMedicine'])->name('hasAcceptedMedicine');
//assing outlet to user
Route::get('/add-user-outlet/{id}', [OutletController::class,'addUser'])->name('addusers');
Route::post('/store-user-outlet', [OutletController::class,'storeUser'])->name('storeuser');

//medicine purchase

Route::get('get-medicine',[MedicineController::class,'get_medicine']);
Route::get('get-medicine-details-for-purchase/{id}',[MedicineController::class,'get_medicine_details_for_purchase']);
Route::get('get-manufacture-wise-medicine',[MedicineController::class,'get_manufacturer_wise_medicine']);
Route::get('get-product-for-sale', [SupplierController::class,'get_product_for_sale']);


//select2 route

Route::post('/get-category',[Select2Controller::class,'getCategory'])->name('get-category');
Route::post('/get-unit',[Select2Controller::class,'getUnit'])->name('get-unit');
Route::post('/get_type',[Select2Controller::class,'getType'])->name('get-type');
Route::post('/get-manufacturer',[Select2Controller::class,'getManufacturer'])->name('get-manufacturer');
Route::get('get-supplier', [Select2Controller::class,'get_supplier']);
Route::get('/get-outlet',[Select2Controller::class,'getOutlet'])->name('get-outlet');
Route::get('/get-all-medicine',[Select2Controller::class,'get_all_medicine'])->name('get-all-medicine');

//stock route
Route::get('/get-outlet-stock/{id}',[OutletStockController::class,'outletStock'])->name('outletstock');
Route::get('/get-warehouse-stock/{id}',[WarehouseStockController::class,'warehouseStock'])->name('warehouseStock');
//profile route
Route::get('/my-profile', [ProfileController::class,'myProfile'])->name('myprofile');
Route::Post('/my-profile/update', [ProfileController::class,'updateMyProfile'])->name('updatemyprofile');

Route::get('/get-customer/{id}',[CustomerManagementController::class,'customer'])->name('get-customer');

//medicine return delete route
Route::get('/delete/{medicineid}/{returnid}/return', [WarehouseReturnController::class,'medicineReturnlDelete'])->name('delete.medicineReturnlDelete');

//stock request route

Route::get('/stock-request/{id}/details', [StockRequestController::class,'details'])->name('stock-request.details');
Route::get('/stock-request/{id}/details/warehouse', [StockRequestController::class,'detailsRequestWarehouse'])->name('detailsRequestWarehouse');
Route::get('/stock-request-details/{medicineid}/{requestid}/delete', [StockRequestController::class,'stockRequestDelete'])->name('stockRequestDelete');
Route::get('/stock/warehouse-request', [StockRequestController::class,'warehouseRequest'])->name('warehouseRequest');

});

Route::group(['middleware' => ['auth'], 'prefix' => 'medicine-setting'],function () {



    Route::resource('category',CategoryController::class);
    Route::resource('unit',UnitController::class);
    Route::resource('manufacturer',ManufacturerController::class);
    Route::resource('medicine',MedicineController::class);
    Route::get('/status-manufacturer/{id}/{status}', [ManufacturerController::class,'active'])->name('manufacturer.active');
});

<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineDistributeController;
use App\Http\Controllers\MedicinePurchaseController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\OutletInvoiceController;
use App\Http\Controllers\OutletStockController;
use App\Http\Controllers\OutletWriteoffController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController2;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\SendMessageLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseReturnController;
use App\Http\Controllers\WarehouseStockController;
use App\Http\Controllers\WarehouseWriteoffController;
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
require __DIR__ . '/auth.php';
Route::get('/', [SettingController::class, 'index']);

Route::get('/track/{code}', [ShortLinkController::class, 'redirect']);


Route::get('/dashboard', [DashBoardController::class, 'index'])->middleware(['auth'])->name('index');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/role', 'admin.role.role')->name('role');

    //user route


    Route::get('/user', [UserRoleController::class, 'users'])->name('user');
    Route::get('/add_user', [UserRoleController::class, 'addUsers'])->name('add_user');
    Route::post('/add-user-store', [UserRoleController::class, 'addUsersStore'])->name('add_user_store');
    Route::get('/delete-user/{id}', [UserRoleController::class, 'deleteUser'])->name('delete_user');
    Route::get('/edit-user/{id}', [UserRoleController::class, 'editUser'])->name('edit_user');
    Route::post('/update/user', [UserRoleController::class, 'updateUser'])->name('updateuser');
    Route::get('/add/user/org/{id}', [UserRoleController::class, 'addUserOrg'])->name('adduserorg');
    Route::post('/store/user/org', [UserRoleController::class, 'storeUserOrg'])->name('storeuserorg');

//permission route

    Route::get('/permission', [PermissionController::class, 'permission'])->name('permission');
    Route::get('/add-permission', [PermissionController::class, 'addPermission'])->name('add_permission');
    Route::post('/store-permission', [PermissionController::class, 'storePermission'])->name('store_permission');
    Route::post('/update-permission', [PermissionController::class, 'updatePermission'])->name('update_permission');
    Route::get('/edit-permission/{id}', [PermissionController::class, 'editPermission'])->name('edit_permission');
    Route::get('/delete-permission/{id}', [PermissionController::class, 'deletePermission'])->name('delete_permission');

    //role route

    Route::get('/role', [RoleController::class, 'role'])->name('role');
    Route::get('/add-role', [RoleController::class, 'addRole'])->name('add_role');
    Route::post('/store-role', [RoleController::class, 'storeRole'])->name('store_role');
    Route::post('/update-role', [RoleController::class, 'updateRole'])->name('update_role');
    Route::get('/edit-role/{id}', [RoleController::class, 'editRole'])->name('edit_role');
    Route::get('/delete-role/{id}', [RoleController::class, 'deleteRole'])->name('delete_role');

    Route::post('/get-role', [RoleController::class, 'getRole'])->name('get_role');

    //add role in permission

    Route::get('/add/role/permission', [RoleController::class, 'addRolePermission'])->name('rolepermission');
    Route::post('/store/role/permission', [RoleController::class, 'storeRolePermission'])->name('add_role_permission');
    Route::get('/all/role/permission', [RoleController::class, 'allRolePermission'])->name('allrolepermission');
    Route::get('/edit/role/permission/{id}', [RoleController::class, 'editRolePermission'])->name('editrolepermission');
    Route::post('/update/role/permission/{id}', [RoleController::class, 'updateRolePermission'])->name('update_role_permission');
    Route::get('/delete/role/permission/{id}', [RoleController::class, 'deleteRolePermission'])->name('deleterolepermission');

    //setting route

    Route::get('/site/setting', [SettingController::class, 'setting'])->name('setting');
    Route::post('/update/setting', [SettingController::class, 'updateSetting'])->name('updatesetting');
    //message
    Route::post('send-message-submit', [SendMessageLogController::class, 'sendMessage'])->name('message.sendMessage');
    Route::get('send-message', [SendMessageLogController::class, 'sendMeessageeView'])->name('sendMessageView');
    Route::get('/send-message-logs', [SendMessageLogController::class, 'index'])->name('sendMessageLogs.index');

});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/top-sale', [DashBoardController::class, 'totalSale'])->name('top-sale');
    Route::get('/top-purchase', [DashBoardController::class, 'totalPurchase'])->name('top-purchase');
    Route::get('/top-customer', [DashBoardController::class, 'topCustomer'])->name('top-customer');
    Route::post('/parse-address', [AddressController::class, 'parseAddress'])->name('parse.address');
    Route::get('/pathao/create-order', [AddressController::class, 'createOrder']);
//resource route
    Route::resource('payment-method', PaymentMethodController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('outlet', OutletController::class);
    Route::resource('product-purchase', MedicinePurchaseController::class);
    Route::resource('warehouse-stock', WarehouseStockController::class);
    Route::resource('outlet-stock', OutletStockController::class);
    Route::resource('distribute-medicine', MedicineDistributeController::class);
    Route::resource('warehouse-return', WarehouseReturnController::class);
    Route::resource('customer', CustomerManagementController::class);
    Route::resource('stock-request', StockRequestController::class);
    Route::resource('invoice', OutletInvoiceController::class);
    Route::resource('outlet-writeoff', OutletWriteoffController::class);
    Route::resource('warehouse-writeoff', WarehouseWriteoffController::class);
    Route::post('warehouse-stock-update', [WarehouseStockController::class, 'warehouse_Stock_Update'])->name('warehouse-stock-update');
    Route::get('/ajax-invoice', [OutletInvoiceController::class, 'ajaxInvoice'])->name('ajax-invoice');
//customer
    Route::get('customer-due/{id}', [CustomerManagementController::class, 'customerDue'])->name('customer-due');
    Route::post('customer-due-payment', [CustomerManagementController::class, 'customerDuePayment'])->name('customer-due-payment');
    Route::get('customer-delete/{id}', [CustomerManagementController::class, 'customerDelete'])->name('customer-delete');

//supplier due

    Route::get('supplier-due/{id}', [SupplierController::class, 'supplierDue'])->name('supplier-due');
    Route::post('supplier-due-payment', [SupplierController::class, 'supplierDuePayment'])->name('supplier-due-payment');

//purchase
    Route::get('edit-purchase/{id}', [MedicinePurchaseController::class, 'editPurchase'])->name('edit-purchase');
    Route::post('purchase-update', [MedicinePurchaseController::class, 'purchaseUpdate'])->name('purchase-update');
    Route::get('purchase-delete/{id}', [MedicinePurchaseController::class, 'purchaseDelete'])->name('purchase-delete');
    Route::get('/get-medicine-purchase', [MedicinePurchaseController::class, 'medicinePurchase'])->name('medicinePurchase');
    Route::get('/medicine-purchase-delete/{id}', [MedicinePurchaseController::class, 'delete'])->name('medicinePurchaseDelete');
//checkin route
    Route::get('medicine-purchase/{id}/check-in', [MedicinePurchaseController::class, 'checkIn'])->name('medicine-purchase.checkIn');
    Route::get('distribute-medicine/{id}/check-in', [MedicineDistributeController::class, 'checkIn'])->name('distribute-medicine.checkIn');
    Route::get('medicine-return/{id}/check-in', [WarehouseReturnController::class, 'checkIn'])->name('medicine-return.checkIn');
    Route::Post('all-in-one', [WarehouseStockController::class, 'allInOne'])->name('all-in-one');
    Route::Post('all-in-one-outlet', [OutletStockController::class, 'allInOne'])->name('all-in-one-outlet');
//medicine route
    Route::get('all-medicines-lists', [MedicineController::class, 'get_all_medicines'])->name('medicine.all-medicines');

//active status route
    Route::get('/delete/{medicineid}/{distributeid}', [MedicineDistributeController::class, 'medicineDistributeDetailDelete'])->name('delete.medicineDistributeDetailDelete');
    Route::get('/status-supplier/{id}/{status}', [SupplierController::class, 'active'])->name('supplier.active');
    Route::get('/status-warehouse/{id}/{status}', [WarehouseController::class, 'active'])->name('warehouse.active');
    Route::get('/status-outlet/{id}/{status}', [OutletController::class, 'active'])->name('outlet.active');
    Route::get('/status-customer/{id}/{status}', [CustomerManagementController::class, 'active'])->name('customer.active');

    Route::get('/has-sent/{id}/{status}', [StockRequestController::class, 'hasSent'])->name('hasSent');
    Route::Post('/has_accepted', [StockRequestController::class, 'hasAccepted'])->name('hasAccepted');
    Route::get('/has_accepted/{id}/{status}/{medicineid}/medicine', [StockRequestController::class, 'hasAcceptedMedicine'])->name('hasAcceptedMedicine');
//assing outlet to user
    Route::get('/add-user-outlet/{id}', [OutletController::class, 'addUser'])->name('addusers');
    Route::post('/store-user-outlet', [OutletController::class, 'storeUser'])->name('storeuser');

//medicine purchase

    Route::get('get-medicine', [MedicineController::class, 'get_medicine']);
    Route::get('get-medicine-details-for-purchase/{id}', [MedicineController::class, 'get_medicine_details_for_purchase']);
    Route::get('get-manufacture-wise-medicine', [MedicineController::class, 'get_manufacturer_wise_medicine']);
    Route::get('get-product-for-sale', [SupplierController::class, 'get_product_for_sale']);

//outlet invoice route

    Route::get('get-medicine-details-for-sale/{id}', [OutletInvoiceController::class, 'get_medicine_details_for_sale']);
    Route::get('/get-outlet-Stock', [OutletInvoiceController::class, 'getoutletStock'])->name('getoutletStock');
    Route::get('/print-invoice/{id}', [OutletInvoiceController::class, 'printInvoice'])->name('print-invoice');

//get warehouse stock

    Route::get('get-medicine-details-warehouse/{id}/{wid}', [WarehouseStockController::class, 'get_medicine_details_warehouse']);
    Route::get('/get-warehouse-Stock/{id}', [WarehouseStockController::class, 'getwarehouseStock'])->name('getwarehouseStock');

//get warehouse stock for return
    Route::get('get-medicine-details-outlet/{id}/{wid}', [OutletStockController::class, 'get_medicine_details_outlet']);
    Route::get('/get-oulet-Stockss/{id}', [OutletStockController::class, 'getoutletStocks'])->name('getoutletStocks');
//select2 route

    Route::post('/get-category', [Select2Controller::class, 'getCategory'])->name('get-category');
    Route::post('/get-unit', [Select2Controller::class, 'getUnit'])->name('get-unit');
    Route::post('/get_type', [Select2Controller::class, 'getType'])->name('get-type');
    Route::post('/get-manufacturer', [Select2Controller::class, 'getManufacturer'])->name('get-manufacturer');
    Route::get('get-supplier', [Select2Controller::class, 'get_supplier']);
    Route::get('/get-outlet', [Select2Controller::class, 'getOutlet'])->name('get-outlet');

    Route::get('/get-all-medicine', [Select2Controller::class, 'get_all_medicine'])->name('get-all-medicine');
    Route::get('get-user', [Select2Controller::class, 'get_user']);
    Route::get('get-user2', [Select2Controller::class, 'get_user2']);
    Route::get('get-payment', [Select2Controller::class, 'get_payment']);
    Route::get('get-user-details/{id}', [Select2Controller::class, 'get_user_details']);
    Route::post('get-category1', [Select2Controller::class, 'get_category'])->name('get-category1');

//stock route
    Route::get('/get-outlet-stock/{id}', [OutletStockController::class, 'outletStock'])->name('outletstock');
    Route::get('/get-outlet-stock2/{id}', [OutletStockController::class, 'outletStock2'])->name('outletstock2');
    Route::get('/get-warehouse-stock/{id}', [WarehouseStockController::class, 'warehouseStock'])->name('warehouseStock');
    Route::get('/get-warehouse-stock2/{id}', [WarehouseStockController::class, 'warehouseStock2'])->name('warehouseStock2 ');
//profile route
    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('myprofile');
    Route::Post('/my-profile/update', [ProfileController::class, 'updateMyProfile'])->name('updatemyprofile');

    //monthly report
    Route::get('/monthly-sales', 'DashBoardController@getMonthlySales')->name('monthly-sales');

//medicine return  route
    Route::get('/delete/{medicineid}/{returnid}/return', [WarehouseReturnController::class, 'medicineReturnlDelete'])->name('delete.medicineReturnlDelete');
    Route::post('/return-recieve', [WarehouseReturnController::class, 'returnRecieve'])->name('returnRecieve');
//stock request route

    Route::get('/stock-request/{id}/details', [StockRequestController::class, 'details'])->name('stock-request.details');
    Route::get('/stock-request/{id}/details/warehouse', [StockRequestController::class, 'detailsRequestWarehouse'])->name('detailsRequestWarehouse');
    Route::get('/stock-request-details/{medicineid}/{requestid}/delete', [StockRequestController::class, 'stockRequestDelete'])->name('stockRequestDelete');
    Route::get('/stock/warehouse-request', [StockRequestController::class, 'warehouseRequest'])->name('warehouseRequest');

//invoice print
    Route::post('/last-invoice', [OutletInvoiceController::class, 'print'])->name('last-invoice.print');
    Route::get('/print-invoice/{id}', [OutletInvoiceController::class, 'printInvoice'])->name('print-invoice');
    Route::get('/print-invoice-exchange/{id}', [\App\Http\Controllers\ExchnageController::class, 'printInvoice'])->name('print-invoice-exchange');

//sales return route

    Route::resource('sale-return', SalesReturnController::class);
    Route::get('sale/{id}/details', [SalesReturnController::class, 'sales_details'])->name('sale.details');
    Route::get('sale-return/{id}/details', [SalesReturnController::class, 'details'])->name('sale-return.details');

    Route::get('/get-customer/{id}', [CustomerManagementController::class, 'customer'])->name('get-customer');

    Route::get('/sale-due/list', [OutletInvoiceController::class, 'dueList'])->name('duelist');
    Route::get('/pay-due/{id}', [OutletInvoiceController::class, 'payDue'])->name('payDue');
    Route::post('/pay-due', [OutletInvoiceController::class, 'paymentDue'])->name('paymentDue');

    Route::get('/exchanges/create', [\App\Http\Controllers\ExchnageController::class,'create'])->name('exchanges.create');
    Route::get('/exchanges', [\App\Http\Controllers\ExchnageController::class,'index'])->name('exchange.index');
    Route::get('/exchanges-details/{id}', [\App\Http\Controllers\ExchnageController::class,'exchangeDetails'])->name('exchange.details');
    Route::get('/get-products/{invoiceId}', [\App\Http\Controllers\ExchnageController::class,'getProducts']);
    Route::post('/exchange', [\App\Http\Controllers\ExchnageController::class,'exchange']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'alert'], function () {

    Route::get('/category-wise-report-alert-outlet', [ReportController2::class, 'category_wise_report_alert_outlet'])->name('category-wise-report-alert-outlet');
    Route::get('/category-wise-report-alert-warehouse', [ReportController2::class, 'category_wise_report_alert_warehouse'])->name('category-wise-report-alert-warehouse');
    Route::post('/category-wise-report-alert-outlet-submit', [ReportController2::class, 'category_wise_report_alert_outlet_submit'])->name('category-wise-report-alert-outlet-submit');
    Route::post('/category-wise-report-alert-warehouse-submit', [ReportController2::class, 'category_wise_report_alert_warehouse_submit'])->name('category-wise-report-alert-warehouse-submit');
});
Route::group(['middleware' => ['auth'], 'prefix' => 'medicine-setting'], function () {

    Route::resource('category', CategoryController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('manufacturer', ManufacturerController::class);
    Route::resource('medicine', MedicineController::class);
    Route::get('/status-manufacturer/{id}/{status}', [ManufacturerController::class, 'active'])->name('manufacturer.active');
});
// Route::group(['middleware' => ['auth'], 'prefix' => 'report'],function () {

//     Route::get('sale-report', [ReportController::class,'medicine_sale_report_form'])->name('sale-report');
//     Route::post('sale-report-submit', [ReportController::class,'medicine_sale_report_submit'])->name('sale-report-submit');

//     Route::get('purchase-report', [ReportController::class,'medicine_purchase_report_form'])->name('purchase-report');
//     Route::post('purchase-report-submit', [ReportController::class,'medicine_purchase_report_submit'])->name('purchase-report-submit');

//     Route::get('outlet-stock', [ReportController::class,'outlet_stock_report_form'])->name('outlet-stock-report');
//     Route::post('outlet-stock-submit', [ReportController::class,'outlet_stock_report_submit'])->name('outlet-stock-submit');

//     Route::get('warehouse-stock', [ReportController::class,'warehouse_stock_report_form'])->name('warehouse-stock-report');
//     Route::post('warehouse-stock-submit', [ReportController::class,'warehouse_stock_report_submit'])->name('warehouse-stock-submit');

// });

Route::group(['middleware' => ['auth'], 'prefix' => 'report2'], function () {

    Route::get('all-report', [ReportController2::class, 'index'])->name('all-report');

    Route::post('sale-report-submit', [ReportController2::class, 'medicine_sale_report_submit'])->name('sale-report-submit');

    Route::post('purchase-report-submit', [ReportController2::class, 'medicine_purchase_report_submit'])->name('purchase-report-submit');

    Route::post('outlet-stock-submit', [ReportController2::class, 'outlet_stock_report_submit'])->name('outlet-stock-submit');

    Route::post('warehouse-stock-submit', [ReportController2::class, 'warehouse_stock_report_submit'])->name('warehouse-stock-submit');

    Route::post('sale-report-user', [ReportController2::class, 'medicine_sale_report_by_user'])->name('sale-report-user');

    Route::post('distribute-medicine-report', [ReportController2::class, 'distribute_medicine_report'])->name('distribute-medicine-report');
    Route::post('stock-request-report', [ReportController2::class, 'stock_request_report'])->name('stock-request-report');

    Route::post('distribute-medicine-report2', [ReportController2::class, 'distribute_medicine_report2'])->name('distribute-medicine-report2');
    Route::post('stock-request-report2', [ReportController2::class, 'stock_request_report2'])->name('stock-request-report2');

    Route::post('return-meidicine-report', [ReportController2::class, 'return_medicine_report'])->name('return-meidicine-report');

    Route::post('return-meidicine-report2', [ReportController2::class, 'return_medicine_report2'])->name('return-meidicine-report2');
    Route::post('sale-report-payment', [ReportController2::class, 'medicine_sale_report_by_payment'])->name('sale-report-payment');

    Route::post('profit-loss-report', [ReportController2::class, 'profit_loss'])->name('profit-loss-report');

    Route::post('sale-return-report', [ReportController2::class, 'medicine_sale_return'])->name('sale-return-report');

    Route::post('sale-report-details', [ReportController2::class, 'medicine_sale_report_details'])->name('sale-report-details');
    Route::get('/sale-report-details1/{id}', [ReportController2::class, 'medicine_sale_report_details1'])->name('sale-report-details1');

    Route::post('/category-wise-report', [ReportController2::class, 'category_wise_report'])->name('category-wise-report');

    Route::post('/expiry-wise-report', [ReportController2::class, 'expiryDate'])->name('expiry-wise-report.warehouse');
    Route::post('/expiry-wise-report1', [ReportController2::class, 'expiryDate1'])->name('expiry-wise-report.outlet');
    Route::post('supplier-wise-report', [ReportController2::class, 'supplier_wise_sale'])->name('supplier-wise-report');
    Route::post('due-payment-report', [ReportController2::class, 'duePaymentReport'])->name('due-payment-report');

    Route::post('supplier-stock-report-outlet', [ReportController2::class, 'supplier_wise_stock_outlet'])->name('supplier-stock-report-outlet');
    Route::post('supplier-stock-report-warehouse', [ReportController2::class, 'supplier_wise_stock_warehouse'])->name('supplier-stock-report-warehouse');

    Route::post('manufacturer-stock-report-outlet', [ReportController2::class, 'manufacturer_wise_stock_outlet'])->name('manufacturer-stock-report-outlet');
    Route::post('manufacturer-stock-report-warehouse', [ReportController2::class, 'manufacturer_wise_stock_warehouse'])->name('manufacturer-stock-report-warehouse');
    Route::post('best-selling', [ReportController2::class, 'bestSelling'])->name('best-selling');
    Route::post('slow-selling', [ReportController2::class, 'slowSelling'])->name('slow-selling');
    Route::post('redeem-point-report', [ReportController2::class, 'redeemPointReport'])->name('redeem-point-report');

    Route::post('sale-report-medicine-submit', [ReportController2::class, 'sale_report_medicine_submit'])->name('sale-report-medicine-submit');
    Route::post('sale-report-manufacturer-submit', [ReportController2::class, 'sale_report_manufacturer_submit'])->name('sale-report-manufacturer-submit');
    Route::post('not-sold-medicine', [ReportController2::class, 'notSoldMedicine'])->name('not-sold-medicine');

});
// Route::group(['middleware' => ['auth'], 'prefix' => 'writeoff'], function () {
//     Route::resource('outlet-writeoff', OutletWriteoffController::class);
//     Route::resource('warehouse-writeoff', WarehouseWriteoffController::class);
// });
// Route::group(['middleware' => ['auth'], 'prefix' => 'summary'], function () {
//     Route::get('outlet', [DashBoardController::class, 'summaryOutlet'])->name('outlet');
//     Route::get('warehouse', [DashBoardController::class, 'summaryWarehouse'])->name('warehouse');
// });



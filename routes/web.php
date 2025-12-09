<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CacheManagementController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\LedgerReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleScreenController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\SaleRecoveryController;
use App\Http\Controllers\SoftwareScreenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('log-js-error', [DashboardController::class, 'logJsError'])->name('js.exception.email');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('auth-login', [CustomAuthController::class, 'customLogin'])->name('auth.login');
Route::get('/installments/calendarData', [InstallmentController::class, 'getcalendarData']);

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgetPassword');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('submitRequest');

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('resetPassword');
Route::post('submitPassword', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('submitPassword');
Route::post('/screenArrangement', [SoftwareScreenController::class, 'updateScreenArrangement'])->name('screen.arrangement');

Route::middleware(['auth', 'lang'])->group(function () {
    Route::get('/', [CustomAuthController::class, 'index']);
    Route::get('setting', [SettingController::class, 'index'])->name('setting');
    Route::post('setting', [SettingController::class, 'store'])->name('setting.store');
    Route::post('smsTemplate', [SettingController::class, 'storeSmsTemplete'])->name('smsTemplate.store');
    Route::post('assignTemplate', [SettingController::class, 'assignTemplate'])->name('assignTemplate.store');
    Route::get('getTemplete/{id}', [SettingController::class, 'getTemplete'])->name('setting.getTemplete');

    Route::middleware(['check.permission:productManagement'])->group(function () {
        Route::get('productManagement', [ProductController::class, 'index'])->name('product.index');
        Route::post('productManagement', [ProductController::class, 'store'])->name('product.store');
        Route::post('/products/check-name', [ProductController::class, 'checkName'])->name('products.checkName');

        Route::put('/productManagement', [ProductController::class, 'update'])->name('product.update');
        Route::delete('productManagement', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('getProducts/{type}', [ProductController::class, 'getProduct'])->name('product.type');
        Route::get('/productDelete/{id}', [ProductController::class, 'delete'])->name('productManagement.delete');


        Route::get('purchaseManagement', [PurchaseController::class, 'index'])->name('purchase.index');
        Route::put('purchaseManagement', [PurchaseController::class, 'update'])->name('purchase.update');
        Route::post('purchaseManagement', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::get('/purchaseDelete/{id}', [PurchaseController::class, 'delete'])->name('purchase.delete');
        Route::get('purchaseProducts/{type}', [PurchaseController::class, 'getProduct'])->name('purchase.get');
    });


    Route::get('/restricted', function () {
        $user = session()->get('user');
        return view('restricted', compact('user'));
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('myProfile');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications.show');
    Route::get('/notifications/list', [DashboardController::class, 'getNotificationList'])->name('notifications.list');
    Route::post('/notifications', [UserController::class, 'markNotificationAsRead'])->name('notifications.mark');
    Route::post('/notifications/mark-all-read', [UserController::class, 'markAllAsRead'])
        ->name('notifications.markAllRead');

    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile', [UserController::class, 'uploadImage'])->name('profile.updateImage');

    Route::middleware(['check.permission:userManagement'])->group(function () {
        Route::get('userManagement', [UserController::class, 'index'])->name('userManagement.index');
        Route::post('userManagement', [UserController::class, 'store'])->name('userManagement.store');
        Route::post('userManagement/1', [UserController::class, 'update'])->name('userManagement.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('userManagement.delete');
        Route::post('/checkUsername', [UserController::class, 'validUsername'])->name('userManagement.validUsername');
        Route::post('userRole', [RoleUserController::class, 'storeId'])->name('userManagement.storeId');
        Route::get('roleManagement-data', [RoleController::class, 'rolesData'])->name('roleManagement.data');
    });
    Route::middleware(['check.permission:roleManagement'])->group(function () {
        Route::get('roleManagement', [RoleController::class, 'index'])->name('roleManagement.index');
        Route::post('roleManagement', [RoleController::class, 'store'])->name('roleManagement.store');
        Route::post('roleManagement/1', [RoleController::class, 'update'])->name('roleManagement.update');
        Route::get('/softDeleteRole/{id}', [RoleController::class, 'delete'])->name('roleManagement.delete');
        Route::get('/softDeleteScreen/{id}', [RoleController::class, 'screenDelete'])->name('roleManagement.screenDelete');
        Route::post('screenTab', [RoleScreenController::class, 'assignTab'])->name('screenTabs.assign');
        Route::get('screenTab/{tabId}', [RoleScreenController::class, 'unassignTab']);
        Route::post('roleScreen', [RoleScreenController::class, 'assign'])->name('roleManagement.assign');
    });
    Route::post('/changePassword/{userId}', [UserController::class, 'changePassword'])->name('userManagement.changePassword');
    Route::post('/validateCurrentPassword', [UserController::class, 'validateCurrentPassword'])->name('userManagement./validateCurrentPassword');

    Route::middleware(['check.permission:screenManagement'])->group(function () {
        Route::get('screenManagement', [SoftwareScreenController::class, 'index'])->name('screenManagement.index');
        Route::post('screenManagement', [SoftwareScreenController::class, 'store'])->name('screenManagement.store');
        Route::post('tabManagement', [SoftwareScreenController::class, 'tabStore'])->name('tabManagement.store');
        Route::post('screenManagement/1', [SoftwareScreenController::class, 'update'])->name('screenManagement.update');
        Route::get('/softDelete/{id}', [SoftwareScreenController::class, 'delete'])->name('screenManagement.delete');
    });
    Route::middleware(['check.permission:bookingManagement'])->group(function () {
        Route::get('bookingManagement', [BookingController::class, 'index'])->name('bookingManagement.index');
        Route::get('bookingManagement', [BookingController::class, 'index'])->name('bookingManagement.index');
        Route::post('bookingManagement', [BookingController::class, 'store'])->name('booking.store');
        Route::get('payinstalment', [InstallmentController::class, 'index'])->name('payinstalment.index');
        Route::get('bookingProducts/{type}', [BookingController::class, 'getProduct'])->name('booking.get');

        Route::get('/booking/{booking}', [BookingController::class, 'getCustomerBookings']);
        Route::get('/instalment/history/{booking}', [InstallmentController::class, 'instalmentHistory']);
        Route::get('/instalment/remaining/{booking}', [InstallmentController::class, 'remainingInstalment']);
        Route::post('/installments/pay', [InstallmentController::class, 'storePayment'])->name('installments.pay');
        Route::get('/receipt/{id}', [InstallmentController::class, 'showReceipt'])->name('receipt.show');
    });

    Route::middleware(['check.permission:recoverySheet'])->group(function () {
        Route::get('recoverySheet', [InstallmentController::class, 'getRecoverySheet'])->name('recoverySheet.index');
    });
    Route::middleware(['check.permission:accountManagement'])->group(function () {
        Route::get('accountManagement', [AccountController::class, 'index'])->name('accountManagement.index');
        Route::post('accountManagement', [AccountController::class, 'store'])->name('accountManagement.store');
        Route::put('accountManagement', [AccountController::class, 'update'])->name('accountManagement.update');
        Route::get('/accountDelete/{id}', [AccountController::class, 'delete'])->name('accountManagement.delete');
        Route::get('/account/{id}', [AccountController::class, 'show'])->name('account.show');
        Route::post('/accounts/check-cnic', [AccountController::class, 'checkCnic']);
        Route::get('/guarantor/{id}', [AccountController::class, 'getGuarantor'])->name('guarantor.show');
        Route::post('/guarantor', [AccountController::class, 'updateGuarantor'])->name('guarantor.update');
        Route::get('/customer-documents/{id}', [AccountController::class, 'getCustomerDocuments'])->name('customer.documents');
        Route::post('/customer-documents', [AccountController::class, 'updateCustomerDocuments'])->name('customer.documents.update');
    });

    Route::middleware(['check.permission:investorManagement'])->group(function () {
        Route::get('investorManagement', [InvestorController::class, 'index'])->name('investorManagement.index');
        Route::post('investorManagement', [InvestorController::class, 'store'])->name('investorManagement.store');
    });
    Route::middleware(['check.permission:investorIncomeReport'])->group(function () {
        Route::get('investorIncomeReport', [ReportController::class, 'investorIncomeReport'])->name('investorIncomeReport.index');
        Route::post('/transferProfit', [InvestmentController::class, 'transferProfit'])
            ->name('transfer.Profit');
    });
    Route::middleware(['check.permission:cashInOut'])->group(function () {
        Route::get('cashInOut', [ReportController::class, 'cashInOutSummary'])->name('report.cashInOutSummary');
    });
    Route::middleware(['check.permission:itemWiseProfitReport'])->group(function () {
        Route::get('itemWiseProfitReport', [ReportController::class, 'itemWiseProfitReport'])->name('reports.item-wise-profit');
    });

    Route::middleware(['check.permission:investmentManagement'])->group(function () {
        Route::get('investmentManagement', [InvestmentController::class, 'index'])->name('investmentManagement.index');
        Route::post('investmentManagement', [InvestmentController::class, 'store'])->name('investmentManagement.store');
    });
    Route::middleware(['check.permission:saleRecovery'])->group(function () {
        Route::get('saleRecovery', [SaleRecoveryController::class, 'index'])->name('saleRecovery.index');
        Route::post('saleRecovery', [SaleRecoveryController::class, 'store'])->name('saleRecovery.store');
        Route::post('saleRecovery/approve', [SaleRecoveryController::class, 'approve'])->name('saleRecovery.approve');
        Route::post('saleRecovery/approveAll', [SaleRecoveryController::class, 'approveAll'])->name('saleRecovery.approveAll');
    });

    Route::middleware(['check.permission:transactions'])->group(function () {
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/getAccountTransaction/{id}', [TransactionController::class, 'getAccountTransaction'])->name('getAccountTransaction.getData');
        Route::get('/getSupplierTransactions/{accountId}', [TransactionController::class, 'getSupplierTransactions']);
    });
    Route::middleware(['check.permission:contractDetailsManagement'])->group(function () {
        Route::get('/contractDetailsManagement', [ReportController::class, 'contractDetails'])->name('contract.details');
        Route::get('/contractDetailsPrint/{booking_id}', [ReportController::class, 'thermalPrint'])->name('thermal.print');
        Route::get('/contractDetailsPrintA5/{booking_id}', [ReportController::class, 'printA5'])->name('thermal.print.a5');
    });
    Route::middleware(['check.permission:blacklistManagement'])->group(function () {
        Route::get('/blacklistManagement', [AccountController::class, 'blackList'])->name('customer.blackList');
        Route::get('/blacklistManagement/{id}', [AccountController::class, 'toggleCustomerStatus'])
            ->name('customer.toggleCustomerStatus');
    });

    Route::get('/cache-management', [CacheManagementController::class, 'index'])->name('cache.management');
    Route::post('/clear-cache', [CacheManagementController::class, 'clearCache'])->name('cache.clear');
    Route::post('/clear-view-cache', [CacheManagementController::class, 'clearViewCache'])->name('cache.clear.view');
    Route::post('/clear-config-cache', [CacheManagementController::class, 'clearConfigCache'])->name('cache.clear.config');
    Route::post('/dump-autoload', [CacheManagementController::class, 'dumpAutoload'])->name('cache.dump.autoload');
    Route::post('/switchLang', [LanguageController::class, 'switchLanguage'])->name('switchLang');

    Route::get('groupManagement', [GroupController::class, 'index'])->name('groupManagement');
    Route::post('groupManagement', [GroupController::class, 'store'])->name('groupManagement.store');
    Route::put('groupManagement', [GroupController::class, 'assign'])->name('groupManagement.assign');
    Route::patch('groupManagement', [GroupController::class, 'update']);
    Route::get('groupManagement/{groupId}', [GroupController::class, 'inActive']);
    Route::get('groupCycle/{groupId}', [GroupController::class, 'updateCycle']);
    Route::get('users/{groupId}', [UserController::class, 'getUsers']);

    Route::get('statusReport', [ReportController::class, 'statusReport'])->name('report.statusReport');
    Route::get('stockReport', [ReportController::class, 'stockReport'])->name('report.stockReport');
    Route::get('/phoneDetails', [ReportController::class, 'phoneDetailsReport'])->name('report.phoneDetailsReport');
    Route::get('/businessReport', [ReportController::class, 'businessReport'])->name('report.businessReport');
    Route::get('/transaction/{booking}', [TransactionController::class, 'getHistory'])->name('transaction.history');
    Route::get('/installmentReport', [ReportController::class, 'installmentReport'])->name('installment.reports');

    Route::middleware(['check.permission:saleReport'])->group(function () {
        Route::get('/saleReport', [SaleReportController::class, 'index'])->name('sale.report');
    });
    Route::middleware(['check.permission:accountLedgerReport'])->group(function () {
        Route::get('/accountLedgerReport', [LedgerReportController::class, 'index'])->name('account.ledger.report');
    });

    Route::post('logout', [CustomAuthController::class, 'logout'])->name('logout');
});

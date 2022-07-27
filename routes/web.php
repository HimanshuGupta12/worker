<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ToolCategoryController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Worker\HourController;
use App\Http\Controllers\HoursController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CompanyDashboardController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::redirect('/', '/hours/index');
//Route::redirect('/', '/dashboard');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['quickadd'])->group(function () {
    Route::get('/scan', [ScanController::class, 'scan'])->name('scan');
    Route::get('/qr/{code}', [QrController::class, 'qr'])->name('qr');
});

/* Keep pdf images for hours as public. */
Route::get('/pdfimage/{hashed_hour_id}', [\App\Http\Controllers\HoursController::class, 'showimage'])->name('pdfimage');

Route::get('/admin9572843', [AdminController::class, 'index']);
Route::get('/admin9572843/{user_id}/login', [AdminController::class, 'login'])->name('admin.login');

// Dynamic service worker for the worker app
Route::get('/manifest/{worker_hash}/manifest.json', \App\Http\Controllers\Worker\ServiceWorkerManifestController::class);
// pwa-uninstaller
Route::get('/uninstall-pwa', [\App\Http\Controllers\Worker\PWAController::class, 'uninstall']);

Route::middleware(['company.access_control', 'quickadd'])->group(function () {
    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_TOOLS')])->group(function () {
        Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
        Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
        Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
        Route::get('/tools/add-more', [ToolController::class, 'addMore'])->name('tools.add-more');
    });
    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_HOURS')])->group(function () {
        Route::post('/hours/images/{hour_id}', [HoursController::class, 'updateImages'])->name('updateImages');
        Route::get('/hours/{hour_id}/edit', [HoursController::class, 'edit'])->name('hours.edit');
        Route::post('/hours/overlapping/{worker_id}', [HoursController::class, 'getOverlapping'])->name('hours.overlapping');
        Route::post('/hours/update', [HoursController::class, 'update'])->name('hours.update');
        Route::post('/hours/comments', [HoursController::class, 'updateComments'])->name('hours.comments');
    });
});

Route::middleware(['auth', 'company.access_control:manager_access', 'quickadd'])->group(function () {

    /*
    1. Verify to exclude deleted records from stats (model, query builders)
    2. Apply company Id filter in all queries.
    3. Include or exclude non-active workers from stats (sickness, holiday etc.)
    3. 
    */
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('company.dashboard');
    
    Route::get('/workers/list/{company_id}', [WorkerController::class, 'getList'])->name('workers.list');
    Route::get('/tools/list/{company_id}/{status}', [ToolController::class, 'getList'])->name('workers.list.status');
    Route::get('/projects/list/{company_id}/{status}', [ProjectController::class, 'getList'])->name('projects.list');

    Route::get('/setting', [\App\Http\Controllers\companySettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [\App\Http\Controllers\companySettingController::class, 'settingSubmition'])->name('setting.settingSubmition');

    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');

    // Route::get('/worker/holiday', [\App\Http\Controllers\ManagerSmsController::class, 'sickness'])->name('worker.sickness');
    // Route::post('/worker/holiday', [\App\Http\Controllers\ManagerSmsController::class, 'sicknessSubmition'])->name('worker.sicknessSubmition');

    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::get('/workers/{worker_id}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::post('/workers/{worker_id}', [WorkerController::class, 'update'])->name('workers.update');
    Route::delete('/workers/{worker_id}', [WorkerController::class, 'destroy'])->name('workers.destroy');
    Route::post('/workers-delete/{worker_id}', [WorkerController::class, 'destroyWithData'])->name('workers.destroy-with-data');
    Route::get('/workers/{worker_id}/report', [WorkerController::class, 'workerReport'])->name('workers.report');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client_id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::post('/clients/{client_id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client_id}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::get('/tool-categories', [ToolCategoryController::class, 'index'])->name('tool-categories.index');
    Route::post('/tool-categories', [ToolCategoryController::class, 'store'])->name('tool-categories.store');
    Route::delete('/tool-categories/{tool_category_id}', [ToolCategoryController::class, 'destroy'])->name('tool-categories.destroy');

    Route::get('/tools/list/{company_id}/{status}', [ToolController::class, 'getList'])->name('workers.list.status');
    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_TOOLS')])->group(function () {
        Route::get('/tools/{tool_id}/edit', [ToolController::class, 'edit'])->name('tools.edit');
        Route::post('/tools/{tool_id}', [ToolController::class, 'update'])->name('tools.update');
        Route::get('/tools/{tool_id}/duplicate', [ToolController::class, 'duplicate'])->name('tools.duplicate');
        Route::post('/tools/{tool_id}/duplicate', [ToolController::class, 'duplicatePost'])->name('tools.duplicate-post');
        Route::delete('/tools/{tool_id}', [ToolController::class, 'destroy'])->name('tools.destroy');
        Route::delete('/tools/{tool_id}/{photo_id}', [ToolController::class, 'deletePhoto'])->name('tools.delete-photo');
        Route::get('/tools/{tool_id}/history', [\App\Http\Controllers\Tool\HistoryController::class, 'index'])->name('tools.histories.index');
        Route::get('/tools/{tool_id}/change-status', [ToolController::class, 'changeStatus'])->name('tools.change-status');
        Route::post('/tools/{tool_id}/change-status', [ToolController::class, 'changeStatusPost']);
    });
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

    Route::get('/storages', [StorageController::class, 'index'])->name('storages.index');
    Route::get('/storages/create', [StorageController::class, 'create'])->name('storages.create');
    Route::post('/storages', [StorageController::class, 'store'])->name('storages.store');
    Route::delete('/storages/{storage_id}', [StorageController::class, 'destroy'])->name('storages.destroy');

    Route::get('/transfer/{tool_id}', [TransferController::class, 'transfer'])->name('transfer');
    Route::post('/transfer/{tool_id}', [TransferController::class, 'store']);

    Route::get('inventorization/edit', [\App\Http\Controllers\InventorizationController::class, 'edit'])->name('inventorization.edit');
    Route::post('inventorization', [\App\Http\Controllers\InventorizationController::class, 'enable'])->name('inventorization.save');
    Route::get('inventorization/disable', [\App\Http\Controllers\InventorizationController::class, 'disable'])->name('inventorization.disable');
    Route::post('inventorization/worker', [\App\Http\Controllers\InventorizationController::class, 'inventoryWorker'])->name('inventorization.worker');
    Route::post('inventorization/storage', [\App\Http\Controllers\InventorizationController::class, 'inventoryStorage'])->name('inventorization.storage');

    Route::get('sms/create', [\App\Http\Controllers\SmsController::class, 'create'])->name('sms.create');
    Route::post('sms', [\App\Http\Controllers\SmsController::class, 'store'])->name('sms.store');

    Route::get('message/create', [\App\Http\Controllers\WorkersMessageController::class, 'create'])->name('message.create');
    Route::post('message', [\App\Http\Controllers\WorkersMessageController::class, 'store'])->name('message.store');

    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_HOURS')])->group(function () {
        // report hours
        Route::get('/hours', [HoursController::class, 'index'])->name('hours.hoursreport');

        Route::get('/hours/index', [HoursController::class, 'index'])->name('hours.index');
        Route::get('/hours/images/{hour_id}', [HoursController::class, 'hourImages'])->name('hourImages');
        Route::put('/hours/update-inline/{hour_id}', [HoursController::class, 'updateInLine']);

        Route::post('/hours/update-report', [HoursController::class, 'updateReport'])->name('hours.update.report');
        Route::get('/hours/update-report-status/{hour_id}', [HoursController::class, 'markAsInvoice'])->name('hours.update.invoice');
        Route::get('/hours/update-status/{hour_id}', [HoursController::class, 'markAsNotInvoice'])->name('hours.update.notinvoice');

        Route::delete('/hours/{hour_id}', [HoursController::class, 'destroy'])->name('hours.destroy.report');
        Route::get('/generate-pdf', [HoursController::class, 'generatePDF'])->name('generatepdf');
        Route::delete('/hour/{hour_id}/{image_nr}', [HoursController::class, 'deleteHourImages'])->name('hour.delete-photo');

        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project_id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::post('/projects/{project_id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::get('/projects/{project_id}/duplicate', [ProjectController::class, 'duplicate'])->name('projects.duplicate');
        Route::post('/projects/{project_id}/duplicate', [ProjectController::class, 'duplicatePost'])->name('projects.duplicate-post');
        Route::delete('/projects-delete/{project_id}', [ProjectController::class, 'destroyWithData'])->name('projects.destroy-with-data');
        Route::post('/projects/{project_id}/update_status', [ProjectController::class, 'update_status'])->name('projects.update_status');
        Route::get('/projects/{project_id}/report', [ProjectController::class, 'projectReport'])->name('projects.report');

        Route::get('/sickness/holiday', [\App\Http\Controllers\SicknessHolidayController::class, 'index'])->name('sickness.holiday');
        Route::post('/sickness/holiday/update', [\App\Http\Controllers\SicknessHolidayController::class, 'changeStatus'])->name('sickness.holiday.update');
    });


    Route::get('/view-settings/hours', [\App\Http\Controllers\SettingController::class, 'hoursSettings'])->name('settings.hours');
    Route::post('/late-submission/update', [\App\Http\Controllers\SettingController::class, 'lateSubmissionUpdate'])->name('settings.latesubmission.update');

    // View Settings
    Route::get('/view-settings', [\App\Http\Controllers\SettingController::class, 'getViewSettings'])->name('settings.getViewSettings');
    Route::post('/view-settings/save', [\App\Http\Controllers\SettingController::class, 'saveViewSettings'])->name('settings.saveViewSettings');
    // View Settings

    Route::get('/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'showSubscription'])->name('subscription.show');
    Route::post('/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'processSubscription'])->name('subscription.process');
    Route::post('/subscribe/free-trial', [\App\Http\Controllers\SubscriptionController::class, 'startFreeTrial'])->name('subscription.startFreeTrial');
    Route::post('/subscribe/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancelSubscription');
    Route::get('/subscribe/invoice', [\App\Http\Controllers\SubscriptionController::class, 'showInvoice'])->name('subscription.invoice');

    Route::post('/worker-position', [\App\Http\Controllers\WorkerPositionController::class, 'store'])->name('settings.workerposition.store');
    Route::post('/worker-position/update', [\App\Http\Controllers\WorkerPositionController::class, 'update'])->name('settings.workerposition.update');
    Route::delete('/worker-position', [\App\Http\Controllers\WorkerPositionController::class, 'delete'])->name('settings.workerposition.delete');

});

Route::middleware(['throttle:5,1'])->group(function () {
    Route::get('/worker/{worker_login_id}/login', [\App\Http\Controllers\Worker\LoginController::class, 'login'])->name('worker.login');
});

Route::get('/pwa/{worker?}', [\App\Http\Controllers\Worker\PWAController::class, 'show'])->name('worker.pwa');
Route::middleware(['auth.worker', 'company.access_control:worker_access'])->group(function () {
    Route::get('/worker', [\App\Http\Controllers\Worker\DashboardController::class, 'index'])->name('worker');
    Route::post('/worker/language', [\App\Http\Controllers\Worker\DashboardController::class, 'language'])->name('worker.language');
    Route::get('/worker/scan', [\App\Http\Controllers\Worker\DashboardController::class, 'scan'])->name('worker.scan');

    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_TOOLS')])->group(function () {
        Route::get('/worker/tools', [\App\Http\Controllers\Worker\ToolController::class, 'index'])->name('worker.tools.index');
        Route::get('/worker/tools/take', [\App\Http\Controllers\Worker\ToolController::class, 'take'])->name('worker.tools.take');
        Route::post('/worker/tools/take-post', [\App\Http\Controllers\Worker\ToolController::class, 'takePost'])->name('worker.tools.take-post');
        Route::get('/worker/tools/inventorize', [\App\Http\Controllers\Worker\ToolController::class, 'inventorize'])->name('worker.tools.inventorize');
        Route::get('/worker/tools/scan-to-storage0', [\App\Http\Controllers\Worker\ToolController::class, 'scanToStorage0'])->name('worker.tools.scan-to-storage0');
        Route::post('/worker/tools/scan-to-storage0', [\App\Http\Controllers\Worker\ToolController::class, 'scanToStorage1'])->name('worker.tools.scan-to-storage1');
        Route::get('/worker/tools/scan-to-storage/{storage_id}', [\App\Http\Controllers\Worker\ToolController::class, 'scanToStorage'])->name('worker.tools.scan-to-storage');
        Route::get('/worker/tools/{tool_id}/change-status', [\App\Http\Controllers\Worker\ToolController::class, 'changeStatus'])->name('worker.tools.change-status');
        Route::post('/worker/tools/{tool_id}/change-status', [\App\Http\Controllers\Worker\ToolController::class, 'changeStatusPost']);
        Route::get('/worker/company-tools', [\App\Http\Controllers\Worker\CompanyToolController::class, 'companyTools'])->name('worker.company-tools.index');
        Route::get('/worker/company-tools/{tool_id}/change-status', [\App\Http\Controllers\Worker\CompanyToolController::class, 'changeStatus'])->name('worker.company-tools.change-status');
        Route::post('/worker/company-tools/{tool_id}/change-status', [\App\Http\Controllers\Worker\CompanyToolController::class, 'changeStatusPost']);
    });

    Route::middleware(['subscription.check:'.config('constants.WORKER_FOR_HOURS')])->group(function () {

        Route::get('/worker/hours', [HourController::class, 'index'])->name('worker.hours');
        Route::get('/worker/{hours_id}/edit', [HourController::class, 'edit'])->name('worker.hours.edit');
        Route::get('/worker/project-hours-by-day/{pid}/{work_day}', [HourController::class, 'getProjectHourByDay'])->name('worker.project.hours.by.day');
        Route::get('/worker/project-hours-by-time/{pid}/{work_day}/{start_time}/{end_time}', [HourController::class, 'getProjectHourByTime'])->name('worker.project.hours.by.time');
        Route::post('/worker/add-hours', [HourController::class, 'store'])->name('hours.store');
        Route::post('/worker/update', [HourController::class, 'update'])->name('worker.update');
        Route::delete('/worker/delete-hours/{hourId}', [HourController::class, 'destroy'])->name('hours.destroy');
        Route::get('/worker/add-more/{hourId}', [HourController::class, 'addMore'])->name('hours.add-more');
        Route::get('/worker/see-hours', [HourController::class, 'seeHour'])->name('worker.seeHours');

        Route::get('/sickworker/holiday', [\App\Http\Controllers\SicknessController::class, 'index'])->name('sickworker.index');
        Route::post('/sickworker/holiday', [\App\Http\Controllers\SicknessController::class, 'sicknessSubmition'])->name('sickworker.sicknessSubmition');
    });

    Route::get('/worker/scan/inventory', [ScanController::class, 'workerInventory'])->name('worker.scan.inventory');
    Route::get('/worker/inventory-storage-choose-storage', [\App\Http\Controllers\Worker\StorageController::class, 'chooseStorage'])->name('worker.inventory-storage-choose-storage');
    Route::get('/worker/inventory-storage/{storage_id}', [\App\Http\Controllers\Worker\StorageController::class, 'inventory'])->name('worker.inventory-storage');

    Route::get('/holiday', [\App\Http\Controllers\HolidayController::class, 'index'])->name('index');
    Route::post('/holiday', [\App\Http\Controllers\HolidayController::class, 'holidaySubmition'])->name('holidaySubmition');


});


// google doc https://docs.google.com/document/d/1weyixWcfXrmp2DjddIKYhXzO-1sPvrA1CKeQ6fdbDik/edit?ts=608ea3d5
// dizainas https://docs.google.com/document/d/1XJPshSpxKQOt_Z7EIkJD8Fjew0PdjhIYqNGJ3loiFoU/edit

// mysql -u doadmin -p -h db-mysql-ams3-94623-do-user-4498384-0.b.db.ondigitalocean.com -P 25060 defaultdb < tools.sql

// https://gruhn.github.io/vue-qrcode-reader/demos/Validate.html

// Super Admin
Route::group(['middleware' => 'auth:super_admin', 'namespace' => 'App\Http\Controllers\SuperAdmin', 'prefix' => 's-admin'], function () {
    Route::get('/companies', 'CompaniesController@index')->name('sa.companies.index');
    Route::get('/access-control-settings', 'CompaniesController@getAccessControlSettings')->name('sa.companies.getAccessControlSettings');
    Route::post('/access-control-settings/save', 'CompaniesController@saveAccessControlSettings')->name('sa.companies.saveAccessControlSettings');
    Route::get('/subscription/{company_id}', 'CompaniesController@subscriptionDetails')->name('sa.companies.subscription');
    Route::post('/subscription/trial', 'CompaniesController@freeTrial')->name('sa.companies.freeTrial');
    Route::post('/subscription/cancel', 'CompaniesController@cancelSubscription')->name('sa.companies.cancelSubscription');
    Route::post('/subscription/coupon', 'CompaniesController@applyCoupon')->name('sa.companies.applyCoupon');
    Route::post('/stripe-customer/create', 'CompaniesController@createStripeCustomer')->name('sa.companies.createStripeCustomer');
    Route::post('/stripe-subscription', 'CompaniesController@stripeSubscriptionDetails')->name('sa.companies.stripeSubscriptionDetails');
    Route::post('/subscription/set-status', 'CompaniesController@setSubscriptionStatus')->name('sa.companies.setSubscriptionStatus');
    Route::get('/run-cron-manually', 'CompaniesController@runTrialEndNotificationCommand')->name('sa.companies.runCommandManually');
});
// Super Admin

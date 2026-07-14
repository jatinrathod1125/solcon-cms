<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/department/switch', [\App\Http\Controllers\Shared\DepartmentController::class, 'switch'])->name('department.switch');

    // Admin Protected Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // User Management CRUD
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');

        // Master Modules CRUD
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->names('admin.departments');
        Route::resource('machines', \App\Http\Controllers\Admin\MachineController::class)->names('admin.machines');
        Route::resource('units', \App\Http\Controllers\Admin\UnitController::class)->names('admin.units');
        Route::resource('bag-sizes', \App\Http\Controllers\Admin\BagSizeController::class)->names('admin.bag-sizes');
        Route::get('raw-materials/export', [\App\Http\Controllers\Admin\RawMaterialController::class, 'exportCsv'])->name('admin.raw-materials.export');
        Route::post('raw-materials/import', [\App\Http\Controllers\Admin\RawMaterialController::class, 'importCsv'])->name('admin.raw-materials.import');
        Route::resource('raw-materials', \App\Http\Controllers\Admin\RawMaterialController::class)->names('admin.raw-materials');
        Route::resource('grades', \App\Http\Controllers\Admin\GradeController::class)->names('admin.grades');
        Route::resource('formulas', \App\Http\Controllers\Admin\FormulaController::class)->names('admin.formulas');
        Route::resource('grout-colors', \App\Http\Controllers\Admin\ColorController::class)->names('admin.grout-colors');
        Route::resource('grout-formulas', \App\Http\Controllers\Admin\GroutFormulaController::class)->names('admin.grout-formulas');
        Route::resource('epoxy-products', \App\Http\Controllers\Admin\EpoxyProductController::class)->names('admin.epoxy-products');
        Route::resource('epoxy-formulas', \App\Http\Controllers\Admin\EpoxyFormulaController::class)->names('admin.epoxy-formulas');
        Route::resource('epoxy-colors', \App\Http\Controllers\Admin\EpoxyFillerColorController::class)->names('admin.epoxy-colors');
        Route::resource('epoxy-components', \App\Http\Controllers\Admin\EpoxyComponentController::class)->names('admin.epoxy-components');
        Route::resource('epoxy-component-formulas', \App\Http\Controllers\Admin\EpoxyComponentFormulaController::class)->names('admin.epoxy-component-formulas');

        // Factory & System Settings (FactoryAdminController)
        Route::get('/settings/factory', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'settingsIndex'])->name('admin.settings.factory');
        Route::post('/settings/factory', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'settingsUpdate'])->name('admin.settings.factory.update');
        Route::get('/settings', function() { return redirect()->route('admin.settings.factory'); })->name('admin.settings.edit');

        // User Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'profileEdit'])->name('admin.profile.edit');
        Route::post('/profile', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'profileUpdate'])->name('admin.profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'passwordUpdate'])->name('admin.profile.password');

        // Activity Logs
        Route::get('/activity-logs', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'activityLogs'])->name('admin.activity-logs');

        // Database Backups
        Route::get('/backups', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'backupsIndex'])->name('admin.backups.index');
        Route::post('/backups', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'backupGenerate'])->name('admin.backups.generate');
        Route::get('/backups/{filename}/download', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'backupDownload'])->name('admin.backups.download');
        Route::delete('/backups/{filename}', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'backupDestroy'])->name('admin.backups.destroy');

        // System Health & Utilities
        Route::get('/system/health', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'systemHealth'])->name('admin.system.health');
        Route::post('/system/utilities', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'clearCache'])->name('admin.system.clear-cache');
        Route::post('/system/email/test', [\App\Http\Controllers\Admin\FactoryAdminController::class, 'sendTestEmail'])->name('admin.system.email-test');

        // Dashboard AJAX Statuses
        Route::get('/dashboard/machines', [AdminDashboardController::class, 'liveMachines'])->name('admin.dashboard.machines');
        Route::get('/calendar/details', [AdminDashboardController::class, 'calendarDetails'])->name('admin.calendar.details');
    });

    // Production Routes (Accessible by Admin and Supervisor)
    Route::middleware(['role:admin,supervisor', 'department.access'])->prefix('production')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProductionController::class, 'index'])->name('production.index');
        Route::get('/create', [\App\Http\Controllers\ProductionController::class, 'create'])->name('production.create');
        Route::post('/', [\App\Http\Controllers\ProductionController::class, 'store'])->name('production.store');
        
        Route::get('/running-active', [\App\Http\Controllers\ProductionController::class, 'runningActive'])->name('production.running_active');
        Route::get('/grades/{grade}/formula', [\App\Http\Controllers\ProductionController::class, 'getFormula'])->name('production.grades.formula');
        
        Route::get('/history', [\App\Http\Controllers\ProductionController::class, 'history'])->name('production.history');
        Route::get('/ledger', [\App\Http\Controllers\ProductionController::class, 'ledger'])->name('production.ledger');
        Route::get('/reports/daily', [\App\Http\Controllers\Admin\ReportController::class, 'dailySummary'])->name('admin.reports.daily');
        Route::get('/reports/daily/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('admin.reports.daily.pdf');
        Route::get('/reports/daily/pdf/whatsapp', [\App\Http\Controllers\Admin\ReportController::class, 'exportWhatsappPdf'])->name('admin.reports.daily.pdf.whatsapp');
        Route::get('/reports/daily/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('admin.reports.daily.excel');
        
        Route::get('/{batch}', [\App\Http\Controllers\ProductionController::class, 'show'])->name('production.show');
        Route::get('/{batch}/complete', [\App\Http\Controllers\ProductionController::class, 'completeForm'])->name('production.complete.form');
        Route::put('/{batch}/complete', [\App\Http\Controllers\ProductionController::class, 'complete'])->name('production.complete');
        Route::post('/{batch}/cancel', [\App\Http\Controllers\ProductionController::class, 'cancel'])->name('production.cancel');
        Route::post('/{batch}/pause', [\App\Http\Controllers\ProductionController::class, 'pause'])->name('production.pause');
        Route::post('/{batch}/resume', [\App\Http\Controllers\ProductionController::class, 'resume'])->name('production.resume');
        Route::post('/{batch}/update-coupon', [\App\Http\Controllers\ProductionController::class, 'updateCoupon'])->name('production.update_coupon');
    });

    // Grout Production Routes (Accessible by Admin and Supervisor)
    Route::middleware(['role:admin,supervisor', 'department.access'])->prefix('grout-production')->group(function () {
        Route::get('/', [\App\Http\Controllers\GroutProductionController::class, 'index'])->name('grout-production.index');
        Route::get('/create', [\App\Http\Controllers\GroutProductionController::class, 'create'])->name('grout-production.create');
        Route::post('/', [\App\Http\Controllers\GroutProductionController::class, 'store'])->name('grout-production.store');
        Route::get('/{batch}', [\App\Http\Controllers\GroutProductionController::class, 'show'])->name('grout-production.show');
        Route::get('/{batch}/running', [\App\Http\Controllers\GroutProductionController::class, 'running'])->name('grout-production.running');

        // Transitions
        Route::post('/{batch}/start-timer', [\App\Http\Controllers\GroutProductionController::class, 'startTimer'])->name('grout-production.start-timer');
        Route::post('/{batch}/skip-timer', [\App\Http\Controllers\GroutProductionController::class, 'skipTimer'])->name('grout-production.skip-timer');
        Route::post('/{batch}/proceed-stage2', [\App\Http\Controllers\GroutProductionController::class, 'proceedToStage2'])->name('grout-production.proceed-stage2');
        Route::post('/{batch}/finish-mixing', [\App\Http\Controllers\GroutProductionController::class, 'finishMixing'])->name('grout-production.finish-mixing');
        Route::post('/{batch}/start-packing', [\App\Http\Controllers\GroutProductionController::class, 'startPacking'])->name('grout-production.start-packing');
        Route::post('/{batch}/complete', [\App\Http\Controllers\GroutProductionController::class, 'complete'])->name('grout-production.complete');

        // Polling Timer status
        Route::get('/{batch}/timer', [\App\Http\Controllers\GroutProductionController::class, 'getTimerStatus'])->name('grout-production.timer-status');
    });

    // Supervisor Protected Routes
    Route::middleware(['role:supervisor', 'department.access'])->prefix('supervisor')->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('supervisor.dashboard');
        Route::get('/dashboard/machines', [SupervisorDashboardController::class, 'liveMachines'])->name('supervisor.dashboard.machines');
    });

    // Epoxy Assembly Routes (Accessible by Admin and Supervisor)
    Route::middleware(['role:admin,supervisor', 'department.access'])->prefix('epoxy-production')->group(function () {
        Route::get('/', [\App\Http\Controllers\EpoxyAssemblyController::class, 'index'])->name('epoxy.index');
        Route::get('/component-entry', [\App\Http\Controllers\EpoxyAssemblyController::class, 'componentEntryForm'])->name('epoxy.component-entry');
        Route::post('/component-entry', [\App\Http\Controllers\EpoxyAssemblyController::class, 'storeComponentEntry'])->name('epoxy.component-entry.store');
        Route::get('/bucket-assembly', [\App\Http\Controllers\EpoxyAssemblyController::class, 'bucketAssemblyForm'])->name('epoxy.bucket-assembly');
        Route::post('/bucket-assembly', [\App\Http\Controllers\EpoxyAssemblyController::class, 'storeBucketAssembly'])->name('epoxy.bucket-assembly.store');
        Route::get('/products/{product}/formula-preview', [\App\Http\Controllers\EpoxyAssemblyController::class, 'previewFormula'])->name('epoxy.formula-preview');
        
        // Backward Compatibility support
        Route::get('/create', function() { return redirect()->route('epoxy.bucket-assembly'); })->name('epoxy.create');
        Route::post('/', [\App\Http\Controllers\EpoxyAssemblyController::class, 'storeBucketAssembly'])->name('epoxy.store');
    });

    // Finished Goods (Admin & Supervisor accessible)
    Route::middleware(['role:admin,supervisor'])->group(function () {
        Route::get('/finished-goods', [\App\Http\Controllers\FinishedGoodsController::class, 'index'])->name('finished-goods.index');
        Route::post('/finished-goods/{finished_good}/adjust', [\App\Http\Controllers\FinishedGoodsController::class, 'adjust'])->name('finished-goods.adjust');
        Route::get('/finished-goods/export', [\App\Http\Controllers\FinishedGoodsController::class, 'export'])->name('finished-goods.export');
        Route::post('/finished-goods/import', [\App\Http\Controllers\FinishedGoodsController::class, 'import'])->name('finished-goods.import');
    });

    // Stock Adjustments (Admin & Supervisor accessible)
    Route::middleware(['role:admin,supervisor'])->prefix('admin')->group(function () {
        Route::get('/stock-adjustments', [\App\Http\Controllers\Admin\StockAdjustmentController::class, 'index'])->name('admin.stock-adjustments.index');
        Route::post('/stock-adjustments', [\App\Http\Controllers\Admin\StockAdjustmentController::class, 'store'])->name('admin.stock-adjustments.store');
    });

    // Notification & FCM Device registration routes
    Route::post('/notifications/devices', [\App\Http\Controllers\NotificationController::class, 'registerDevice'])->name('notifications.devices.register');
    Route::delete('/notifications/devices', [\App\Http\Controllers\NotificationController::class, 'removeDevice'])->name('notifications.devices.remove');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Todo Widget AJAX routes
    Route::post('/todos', [\App\Http\Controllers\Shared\TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [\App\Http\Controllers\Shared\TodoController::class, 'update'])->name('todos.update');
    Route::post('/todos/{todo}/toggle', [\App\Http\Controllers\Shared\TodoController::class, 'toggle'])->name('todos.toggle');
    Route::post('/todos/{todo}/status', [\App\Http\Controllers\Shared\TodoController::class, 'status'])->name('todos.status');
    Route::post('/todos/{todo}/reorder', [\App\Http\Controllers\Shared\TodoController::class, 'reorder'])->name('todos.reorder');
    Route::delete('/todos/{todo}', [\App\Http\Controllers\Shared\TodoController::class, 'destroy'])->name('todos.destroy');

    // Marketing Department Order Board Routes
    Route::middleware('role:admin,supervisor,marketing')->prefix('marketing')->group(function () {
        Route::get('/orders', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'index'])->name('marketing.orders.index');
        Route::post('/orders', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'store'])->name('marketing.orders.store');
        Route::get('/orders/{order}', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'show'])->name('marketing.orders.show');
        Route::put('/orders/{order}', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'update'])->name('marketing.orders.update');
        Route::post('/orders/{order}/status', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'status'])->name('marketing.orders.status');
        Route::post('/orders/{order}/reorder', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'reorder'])->name('marketing.orders.reorder');
        Route::post('/orders/{order}/complete', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'complete'])->name('marketing.orders.complete');
        Route::delete('/orders/{order}', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'destroy'])->name('marketing.orders.destroy');
        Route::post('/orders/refresh-availability', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'refresh'])->name('marketing.orders.refresh');
        
        // API endpoints for dynamic stock checking
        Route::get('/api/product-stock', [\App\Http\Controllers\Marketing\MarketingOrderController::class, 'productStock'])->name('marketing.api.product_stock');
    });
});

// Root Route Redirection
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.dashboard');
        }
        if ($user->isMarketing()) {
            return redirect()->route('marketing.orders.index');
        }
    }
    return redirect()->route('login');
});

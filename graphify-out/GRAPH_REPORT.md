# Graph Report - .  (2026-08-15)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1708 nodes · 3417 edges · 292 communities (212 shown, 80 thin omitted)
- Extraction: 87% EXTRACTED · 13% INFERRED · 0% AMBIGUOUS · INFERRED: 443 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `4bbedc8a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Database\Eloquent\Relations\BelongsTo
- MarketingOrder
- GroutFormula
- Brand
- Role
- Color
- User
- package.json
- TestCase
- Illuminate\Foundation\Http\FormRequest
- Grade
- EpoxyComponentFormula
- EpoxyProduct
- Dispatch
- ProductionBatch
- BagSize
- FactoryAdminController
- FinishedGood
- Department
- EpoxyFormula
- PackingMaterial
- Unit
- MarketingOrderItem
- EpoxyComponent
- RawMaterial
- GroutProductionBatch
- DispatchItem
- ColorController
- EpoxyFillerColorController
- GradeController.php
- MachineController
- Todo
- EpoxyAssembly
- manifest.json
- self
- ActivityLogService
- Notification
- scripts
- TestFixtureSeeder.php
- SettingService
- Closure
- UserDevice
- composer.json
- Controller
- Formula
- Setting
- Illuminate\Http\Request
- DashboardService
- Illuminate\Database\Eloquent\Relations\HasMany
- static
- FinishedGoodsResolver
- NotificationService
- FormulaController.php
- .log
- MarketingOrderTest
- GroutProductionController.php
- ProductionController.php
- AuthTest
- currentDepartment
- EnterpriseTest
- FactoryAdminTest
- MastersTest
- MaintenanceUnlockController.php
- config
- require-dev
- setup
- RawMaterialsTest
- PackingMaterialController.php
- DailyReportService
- DispatchLoadingService
- FinishedGoodsController
- FinishedGoodsService
- psr-4
- GroutColorTest
- ProductionPlanningTest
- CompleteGroutProductionRequest
- AppServiceProvider
- DepartmentAccessService
- GradesTest
- ReportsTest
- StoreGroutProductionRequest
- LoginController
- CompleteProductionBatchRequest
- require
- firebase.js
- FormulasTest.php
- ExampleTest
- sw.js
- .department
- HasMany
- extra
- post-autoload-dump
- admin.stock_adjustments._table
- .ledgers
- .getDailyReportData
- finished_goods._table
- partials.pwa
- bag_sizes/create.blade.php
- bag_sizes/edit.blade.php
- colors/create.blade.php
- colors/edit.blade.php
- admin/dashboard.blade.php
- departments/create.blade.php
- departments/edit.blade.php
- epoxy_formulas/create.blade.php
- epoxy_formulas/edit.blade.php
- epoxy_products/create.blade.php
- epoxy_products/edit.blade.php
- formulas/create.blade.php
- formulas/edit.blade.php
- grades/create.blade.php
- grades/edit.blade.php
- grout_formulas/create.blade.php
- grout_formulas/edit.blade.php
- machines/create.blade.php
- machines/edit.blade.php
- packing_materials/create.blade.php
- packing_materials/edit.blade.php
- raw_materials/create.blade.php
- raw_materials/edit.blade.php
- units/create.blade.php
- units/edit.blade.php
- users/create.blade.php
- users/edit.blade.php
- auth.blade.php
- supervisor/dashboard.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 123 edges
2. `RawMaterial` - 95 edges
3. `Department` - 75 edges
4. `Controller` - 65 edges
5. `Grade` - 60 edges
6. `Unit` - 58 edges
7. `Machine` - 55 edges
8. `ActivityLogService` - 50 edges
9. `TestCase` - 48 edges
10. `FinishedGood` - 44 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Department`  [INFERRED]
  database/migrations/2026_06_29_000004_create_grout_production_batches_table.php → app/Models/Department.php
- `up()` --calls--> `Department`  [INFERRED]
  database/migrations/2026_07_10_160004_seed_all_coupons.php → app/Models/Department.php
- `down()` --calls--> `User`  [INFERRED]
  database/migrations/2026_07_10_160003_seed_marketing_role.php → app/Models/User.php
- `up()` --calls--> `User`  [INFERRED]
  database/migrations/2026_07_10_160003_seed_marketing_role.php → app/Models/User.php
- `down()` --calls--> `User`  [INFERRED]
  database/migrations/2026_07_21_180000_create_dispatch_management_system_tables.php → app/Models/User.php

## Import Cycles
- None detected.

## Communities (292 total, 80 thin omitted)

### Community 0 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.05
Nodes (10): DispatchLoadingLog, DispatchStatusHistory, EpoxyComponentMapping, EpoxyComponentPreparation, FormulaItem, GroutFormulaItem, StockAdjustment, StockLedger (+2 more)

### Community 1 - "MarketingOrder"
Cohesion: 0.07
Nodes (4): MarketingOrderController, MarketingOrder, MarketingOrderService, Illuminate\Support\Collection

### Community 2 - "GroutFormula"
Cohesion: 0.07
Nodes (6): GroutFormulaController, StoreGroutFormulaRequest, UpdateGroutFormulaRequest, GroutFormula, GroutFormulaService, GroutFormulaTest

### Community 3 - "Brand"
Cohesion: 0.09
Nodes (9): BrandController, BrandController, StoreBrandRequest, UpdateBrandRequest, Brand, BrandContextService, BrandSeeder, TestFixtureSeeder (+1 more)

### Community 4 - "Role"
Cohesion: 0.09
Nodes (9): Permission, Role, down(), up(), down(), up(), DatabaseSeeder, Illuminate\Database\Eloquent\Relations\BelongsToMany (+1 more)

### Community 5 - "Color"
Cohesion: 0.11
Nodes (4): Color, EpoxyFillerColor, FinishedGoodsComprehensiveSeeder, Illuminate\Database\Eloquent\Relations\HasOne

### Community 6 - "User"
Cohesion: 0.12
Nodes (5): Collection, User, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, DashboardTest

### Community 7 - "package.json"
Cohesion: 0.07
Nodes (26): ag-grid-community, concurrently, firebase, @formkit/auto-animate, laravel-vite-plugin, dependencies, ag-grid-community, firebase (+18 more)

### Community 8 - "TestCase"
Cohesion: 0.18
Nodes (4): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 9 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.09
Nodes (7): StoreRawMaterialRequest, UpdatePackingMaterialRequest, UpdateRawMaterialRequest, CompleteProductionRequest, StoreProductionBatchRequest, StoreProductionRequest, Illuminate\Foundation\Http\FormRequest

### Community 10 - "Grade"
Cohesion: 0.13
Nodes (4): Grade, Machine, up(), ProductionTest

### Community 11 - "EpoxyComponentFormula"
Cohesion: 0.13
Nodes (4): EpoxyComponentFormulaController, EpoxyComponentFormula, EpoxyComponentFormulaItem, EpoxyModuleSeeder

### Community 12 - "EpoxyProduct"
Cohesion: 0.13
Nodes (4): EpoxyProductController, EpoxyAssemblyController, EpoxyProduct, EpoxyAssemblyService

### Community 14 - "ProductionBatch"
Cohesion: 0.16
Nodes (3): ProductionController, ProductionBatch, ProductionService

### Community 15 - "BagSize"
Cohesion: 0.13
Nodes (4): BagSizeController, StoreBagSizeRequest, UpdateBagSizeRequest, BagSize

### Community 16 - "FactoryAdminController"
Cohesion: 0.11
Nodes (3): FactoryAdminController, ActivityLog, SystemService

### Community 18 - "Department"
Cohesion: 0.15
Nodes (5): DepartmentController, StoreDepartmentRequest, UpdateDepartmentRequest, Department, BelongsToMany

### Community 19 - "EpoxyFormula"
Cohesion: 0.15
Nodes (3): EpoxyFormulaController, EpoxyFormula, EpoxyFormulaItem

### Community 20 - "PackingMaterial"
Cohesion: 0.16
Nodes (3): PackingMaterialController, PackingMaterial, PackingMaterialCategory

### Community 21 - "Unit"
Cohesion: 0.15
Nodes (4): UnitController, StoreUnitRequest, UpdateUnitRequest, Unit

### Community 24 - "RawMaterial"
Cohesion: 0.18
Nodes (4): RawMaterialController, RawMaterial, down(), up()

### Community 27 - "ColorController"
Cohesion: 0.14
Nodes (3): ColorController, StoreColorRequest, UpdateColorRequest

### Community 28 - "EpoxyFillerColorController"
Cohesion: 0.14
Nodes (3): EpoxyFillerColorController, StoreEpoxyColorRequest, UpdateEpoxyColorRequest

### Community 29 - "GradeController.php"
Cohesion: 0.14
Nodes (3): GradeController, StoreGradeRequest, UpdateGradeRequest

### Community 30 - "MachineController"
Cohesion: 0.14
Nodes (3): MachineController, StoreMachineRequest, UpdateMachineRequest

### Community 31 - "Todo"
Cohesion: 0.19
Nodes (3): TodoController, Todo, TodoTest

### Community 34 - "manifest.json"
Cohesion: 0.12
Nodes (15): background_color, categories, description, display, icons, name, orientation, scope (+7 more)

### Community 36 - "ActivityLogService"
Cohesion: 0.20
Nodes (3): UserController, ActivityLogService, GroutProductionService

### Community 38 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, dev, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall (+7 more)

### Community 40 - "SettingService"
Cohesion: 0.23
Nodes (3): MaintenanceController, SettingService, EnterpriseMaintenanceModeTest

### Community 41 - "Closure"
Cohesion: 0.26
Nodes (6): DepartmentAccessMiddleware, MaintenanceMiddleware, PermissionMiddleware, RoleMiddleware, Closure, Symfony\Component\HttpFoundation\Response

### Community 42 - "UserDevice"
Cohesion: 0.16
Nodes (3): UserDevice, Illuminate\Database\Eloquent\Factories\HasFactory, FirebaseNotificationTest

### Community 43 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 44 - "Controller"
Cohesion: 0.19
Nodes (4): DashboardController, StockAdjustmentController, Controller, DepartmentController

### Community 50 - "static"
Cohesion: 0.20
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 52 - "NotificationService"
Cohesion: 0.27
Nodes (4): CheckMixingTimers, NotificationService, Collection, Illuminate\Console\Command

### Community 60 - "EnterpriseTest"
Cohesion: 0.28
Nodes (3): format_quantity(), StockService, EnterpriseTest

### Community 64 - "config"
Cohesion: 0.25
Nodes (8): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, process-timeout, sort-packages

### Community 65 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 66 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 74 - "psr-4"
Cohesion: 0.29
Nodes (7): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\, app/Helpers/helpers.php

### Community 85 - "require"
Cohesion: 0.40
Nodes (5): require, barryvdh/laravel-dompdf, laravel/framework, laravel/tinker, php

### Community 86 - "firebase.js"
Cohesion: 0.60
Nodes (3): getBrowserAndOS(), registerTokenOnServer(), requestPermissionAndGetToken()

### Community 89 - "sw.js"
Cohesion: 0.50
Nodes (3): firebaseConfig, PRECACHE_ASSETS, urlParams

### Community 92 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 93 - "post-autoload-dump"
Cohesion: 0.67
Nodes (3): post-autoload-dump, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan package:discover --ansi

## Knowledge Gaps
- **110 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+105 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **80 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `Illuminate\Database\Eloquent\Relations\BelongsTo`, `GroutFormula`, `Role`, `TestCase`, `Grade`, `EpoxyComponentFormula`, `EpoxyProduct`, `Dispatch`, `FactoryAdminController`, `FinishedGood`, `Todo`, `EpoxyAssembly`, `ActivityLogService`, `TestFixtureSeeder.php`, `SettingService`, `UserDevice`, `NotificationService`, `.log`, `AuthTest`, `currentDepartment`, `EnterpriseTest`, `FactoryAdminTest`, `MastersTest`, `RawMaterialsTest`, `SystemService.php`, `GroutColorTest`, `ProductionPlanningTest`, `DepartmentAccessService`, `GradesTest`, `ReportsTest`, `FormulasTest.php`, `.department`, `HasMany`?**
  _High betweenness centrality (0.063) - this node is a cross-community bridge._
- **Why does `TestCase` connect `TestCase` to `GroutFormula`, `Role`, `User`, `package.json`, `Grade`, `FinishedGood`, `Todo`, `EpoxyAssembly`, `Grade.php`, `TestFixtureSeeder.php`, `SettingService`, `UserDevice`, `MarketingOrderTest`, `AuthTest`, `EnterpriseTest`, `FactoryAdminTest`, `MastersTest`, `RawMaterialsTest`, `GroutColorTest`, `ProductionPlanningTest`, `GradesTest`, `ReportsTest`, `FormulasTest.php`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Why does `RawMaterial` connect `RawMaterial` to `Illuminate\Database\Eloquent\Relations\BelongsTo`, `MarketingOrder`, `GroutFormula`, `Color`, `TestCase`, `Grade`, `EpoxyComponentFormula`, `EpoxyProduct`, `ProductionBatch`, `EpoxyFormula`, `MarketingOrderItem`, `EpoxyComponent`, `EpoxyAssembly`, `Grade.php`, `TestFixtureSeeder.php`, `Controller`, `Formula`, `DashboardService`, `FormulaController.php`, `MarketingOrderTest`, `ProductionController.php`, `currentDepartment`, `EnterpriseTest`, `RawMaterialsTest`, `FinishedGoodsController`, `FormulasTest.php`?**
  _High betweenness centrality (0.044) - this node is a cross-community bridge._
- **Are the 81 inferred relationships involving `User` (e.g. with `.activityLogs()` and `.create()`) actually correct?**
  _`User` has 81 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _110 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Database\Eloquent\Relations\BelongsTo` be split into smaller, more focused modules?**
  _Cohesion score 0.0546448087431694 - nodes in this community are weakly interconnected._
- **Should `MarketingOrder` be split into smaller, more focused modules?**
  _Cohesion score 0.06802721088435375 - nodes in this community are weakly interconnected._
# Graph Report - .  (2026-08-13)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1668 nodes · 3347 edges · 291 communities (202 shown, 89 thin omitted)
- Extraction: 87% EXTRACTED · 13% INFERRED · 0% AMBIGUOUS · INFERRED: 440 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `d076587e`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- User
- MarketingOrder
- BagSize
- SettingService
- Illuminate\Http\Request
- User.php
- TestCase
- package.json
- GroutFormula
- Color
- Department
- GroutProductionBatch
- ProductionBatch
- Machine
- EpoxyFormula
- EpoxyAssembly
- Illuminate\Database\Eloquent\Relations\BelongsTo
- NotificationService
- FactoryAdminController
- FinishedGood
- EpoxyComponentFormula
- MarketingOrderItem
- EpoxyComponent
- Illuminate\Database\Eloquent\Model
- EpoxyProduct
- Controller
- Todo
- Illuminate\Foundation\Http\FormRequest
- DispatchItem
- Grade
- manifest.json
- ColorController
- self
- Notification
- scripts
- Closure
- Illuminate\Database\Eloquent\Relations\HasMany
- ActivityLogService
- composer.json
- Unit
- RawMaterial
- .prepareComponent
- DashboardService
- Formula
- FinishedGoodsResolver
- PackingMaterial
- MarketingOrderTest
- EpoxyFillerColor
- PackingMaterialController
- StockLedger
- AuthTest
- MastersTest
- currentDepartment
- .log
- RawMaterialsTest
- FinishedGoodsService
- config
- require-dev
- setup
- DailyReportService
- DepartmentAccessService
- FinishedGoodsController
- FormulaItem
- psr-4
- ReportsTest.php
- DispatchLoadingService
- CompleteProductionRequest
- StoreProductionRequest
- DashboardController
- AppServiceProvider
- StoreEpoxyColorRequest
- UpdateEpoxyColorRequest
- StoreGradeRequest
- UpdateGradeRequest
- StoreMachineRequest
- UpdateMachineRequest
- StorePackingMaterialRequest
- StoreRawMaterialRequest
- .__construct
- StoreGroutProductionRequest
- LoginController
- StoreFormulaRequest
- StoreGroutFormulaRequest
- UpdateFormulaRequest
- UpdateGroutFormulaRequest
- CompleteGroutProductionRequest
- CompleteProductionBatchRequest
- GroutFormulaItem
- require
- firebase.js
- UpdatePackingMaterialRequest
- ExampleTest
- sw.js
- extra
- post-autoload-dump
- admin.stock_adjustments._table
- .ledgers
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
- ActivityLog

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

## Communities (291 total, 89 thin omitted)

### Community 0 - "User"
Cohesion: 0.06
Nodes (9): UserController, BelongsTo, Collection, HasMany, User, Illuminate\Foundation\Auth\User, DashboardTest, FactoryAdminTest (+1 more)

### Community 1 - "MarketingOrder"
Cohesion: 0.07
Nodes (4): MarketingOrderController, MarketingOrder, MarketingOrderService, Illuminate\Support\Collection

### Community 2 - "BagSize"
Cohesion: 0.07
Nodes (8): BagSizeController, StoreBagSizeRequest, UpdateBagSizeRequest, BagSize, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static, GradesTest

### Community 3 - "SettingService"
Cohesion: 0.08
Nodes (7): SettingsController, MaintenanceController, MaintenanceUnlockController, UnlockRequest, Setting, SettingService, EnterpriseMaintenanceModeTest

### Community 4 - "Illuminate\Http\Request"
Cohesion: 0.11
Nodes (4): DispatchController, Dispatch, DispatchService, Illuminate\Http\Request

### Community 5 - "User.php"
Cohesion: 0.08
Nodes (10): Role, down(), up(), down(), up(), DatabaseSeeder, Illuminate\Database\Eloquent\Relations\BelongsToMany, Illuminate\Notifications\Notifiable (+2 more)

### Community 6 - "TestCase"
Cohesion: 0.08
Nodes (7): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, ExampleTest, FormulasTest, GroutColorTest, GroutProductionTest, TestCase

### Community 7 - "package.json"
Cohesion: 0.07
Nodes (26): ag-grid-community, concurrently, firebase, @formkit/auto-animate, laravel-vite-plugin, dependencies, ag-grid-community, firebase (+18 more)

### Community 8 - "GroutFormula"
Cohesion: 0.10
Nodes (4): GroutFormulaController, GroutFormula, GroutFormulaService, GroutFormulaTest

### Community 10 - "Department"
Cohesion: 0.10
Nodes (6): DepartmentController, MachineController, StoreDepartmentRequest, UpdateDepartmentRequest, Department, BelongsToMany

### Community 11 - "GroutProductionBatch"
Cohesion: 0.13
Nodes (3): GroutProductionController, GroutProductionBatch, MixTimerService

### Community 12 - "ProductionBatch"
Cohesion: 0.14
Nodes (4): ProductionController, ProductionBatch, ProductionService, ReportService

### Community 13 - "Machine"
Cohesion: 0.13
Nodes (4): Machine, up(), EnterpriseTest, ProductionTest

### Community 14 - "EpoxyFormula"
Cohesion: 0.13
Nodes (4): EpoxyFormulaController, EpoxyFormula, EpoxyFormulaItem, TestFixtureSeeder

### Community 15 - "EpoxyAssembly"
Cohesion: 0.10
Nodes (3): EpoxyAssembly, EpoxyComponentPreparation, EpoxyAssemblyTest

### Community 17 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.11
Nodes (3): DispatchStatusHistory, EpoxyComponentMapping, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 18 - "NotificationService"
Cohesion: 0.14
Nodes (6): CheckMixingTimers, UserDevice, NotificationService, Collection, Illuminate\Console\Command, Illuminate\Database\Eloquent\Factories\HasFactory

### Community 21 - "EpoxyComponentFormula"
Cohesion: 0.15
Nodes (3): EpoxyComponentFormulaController, EpoxyComponentFormula, EpoxyComponentFormulaItem

### Community 24 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.13
Nodes (4): DispatchLoadingLog, Permission, StockAdjustment, Illuminate\Database\Eloquent\Model

### Community 27 - "Todo"
Cohesion: 0.18
Nodes (3): TodoController, Todo, TodoTest

### Community 28 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.15
Nodes (5): StoreUnitRequest, UpdateRawMaterialRequest, UpdateUnitRequest, StoreProductionBatchRequest, Illuminate\Foundation\Http\FormRequest

### Community 30 - "Grade"
Cohesion: 0.15
Nodes (3): GradeController, Grade, FormulaService

### Community 31 - "manifest.json"
Cohesion: 0.12
Nodes (15): background_color, categories, description, display, icons, name, orientation, scope (+7 more)

### Community 32 - "ColorController"
Cohesion: 0.13
Nodes (3): ColorController, StoreColorRequest, UpdateColorRequest

### Community 35 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, dev, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall (+7 more)

### Community 36 - "Closure"
Cohesion: 0.26
Nodes (6): DepartmentAccessMiddleware, MaintenanceMiddleware, PermissionMiddleware, RoleMiddleware, Closure, Symfony\Component\HttpFoundation\Response

### Community 38 - "ActivityLogService"
Cohesion: 0.22
Nodes (3): ActivityLogService, BatchNumberService, GroutProductionService

### Community 39 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 41 - "RawMaterial"
Cohesion: 0.15
Nodes (6): RawMaterialController, StockAdjustmentController, RawMaterial, StockService, down(), up()

### Community 48 - "EpoxyFillerColor"
Cohesion: 0.22
Nodes (5): EpoxyFillerColorController, EpoxyFillerColor, EpoxyModuleSeeder, FinishedGoodsComprehensiveSeeder, Illuminate\Database\Seeder

### Community 53 - "currentDepartment"
Cohesion: 0.25
Nodes (3): availableDepartments(), currentDepartment(), format_quantity()

### Community 57 - "config"
Cohesion: 0.25
Nodes (8): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, process-timeout, sort-packages

### Community 58 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 59 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 65 - "psr-4"
Cohesion: 0.29
Nodes (7): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\, app/Helpers/helpers.php

### Community 90 - "require"
Cohesion: 0.40
Nodes (5): require, barryvdh/laravel-dompdf, laravel/framework, laravel/tinker, php

### Community 91 - "firebase.js"
Cohesion: 0.60
Nodes (3): getBrowserAndOS(), registerTokenOnServer(), requestPermissionAndGetToken()

### Community 94 - "sw.js"
Cohesion: 0.50
Nodes (3): firebaseConfig, PRECACHE_ASSETS, urlParams

### Community 95 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 96 - "post-autoload-dump"
Cohesion: 0.67
Nodes (3): post-autoload-dump, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan package:discover --ansi

## Knowledge Gaps
- **110 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+105 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **89 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `BagSize`, `SettingService`, `Illuminate\Http\Request`, `User.php`, `TestCase`, `GroutFormula`, `Department`, `Machine`, `EpoxyFormula`, `EpoxyAssembly`, `NotificationService`, `FinishedGood`, `Illuminate\Database\Eloquent\Model`, `Controller`, `Todo`, `ActivityLog`, `RawMaterial`, `.prepareComponent`, `AuthTest`, `MastersTest`, `currentDepartment`, `RawMaterialsTest`, `DepartmentAccessService`, `FactoryAdminController.php`, `ReportsTest.php`?**
  _High betweenness centrality (0.063) - this node is a cross-community bridge._
- **Why does `RawMaterial` connect `RawMaterial` to `MarketingOrder`, `TestCase`, `GroutFormula`, `Color`, `ProductionBatch`, `Machine`, `EpoxyFormula`, `EpoxyAssembly`, `Grade.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `EpoxyComponentFormula`, `MarketingOrderItem`, `EpoxyComponent`, `Illuminate\Database\Eloquent\Model`, `Controller`, `ActivityLogService`, `.prepareComponent`, `DashboardService`, `Formula`, `PackingMaterial`, `MarketingOrderTest`, `EpoxyFillerColor`, `currentDepartment`, `RawMaterialsTest`, `FinishedGoodsController`, `ReportsTest.php`, `StoreRawMaterialRequest`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Why does `TestCase` connect `TestCase` to `User`, `BagSize`, `SettingService`, `ReportsTest.php`, `User.php`, `package.json`, `GroutFormula`, `Color`, `Machine`, `EpoxyAssembly`, `Grade.php`, `MarketingOrderTest`, `AuthTest`, `FinishedGood`, `MastersTest`, `RawMaterialsTest`, `Todo`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Are the 81 inferred relationships involving `User` (e.g. with `.activityLogs()` and `.create()`) actually correct?**
  _`User` has 81 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _110 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.055523085914669784 - nodes in this community are weakly interconnected._
- **Should `MarketingOrder` be split into smaller, more focused modules?**
  _Cohesion score 0.06693877551020408 - nodes in this community are weakly interconnected._
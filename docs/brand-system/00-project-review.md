# Solcon CMS Multi-Brand System: Project Review

## Current Architecture
- Laravel 11 framework
- 42 models, 24 services
- Services like `ProductionService`, `StockService`, `ActivityLogService` heavily use static methods.
- Roles defined: `admin`, `supervisor`, `super_admin`, `marketing`, `dispatch`.

## Models Overview
- **ProductionBatch**: Fields include `batch_no`, `grade_id`, `formula_id`, `formula_snapshot`, `coupon_raw_material_id`.
- **GroutProductionBatch**: Specific fields like `color_id`, `stages`, `timers`.
- **EpoxyAssembly**: Comprises `epoxy_product_id`, `epoxy_component_id`, `color_id`.
- **FinishedGood**: References `department_id` (foreign key, not code), `grade_id`, `color_id`, `epoxy_product_id`, `epoxy_component_id`, `epoxy_filler_color_id`, `coupon_raw_material_id`, `packing`, `available_bags`, `available_weight`, `status`.

## Services Overview
- **ProductionService**: Static methods (`startBatch`, `completeBatch`, `cancelBatch`, `pauseBatch`, `resumeBatch`).
- **GroutProductionService**: Handles timer and stage logic with an M-01 restriction.
- **EpoxyAssemblyService**: Manages component preparation and bucket assembly.
- **FinishedGoodsService**: Operations to increment stock (`incrementAdhesiveStock`, `incrementGroutStock`, `incrementEpoxyStock`).
- **FinishedGoodsResolver**: Contains logic to find stock for order/dispatch items or attributes, with packing normalization and fallback logic.
- **StockService**: Static `recordMovement` method taking `raw_material_id` or `packing_material_id`.

## Additional Logic Details
- **FormulaItems**: Distinguish between `item_type` (`raw` or `packing`) and define `consumption_method`.
- **Finished Goods Identity**:
  - Tile Adhesive (TAD): `grade_id`, `packing`, `coupon`
  - Grout (GRT): `color_id`, `packing`
  - Epoxy (EPX): `epoxy_product_id`, `color_id`, `epoxy_filler_color_id`, `packing`
- **Orders/Dispatch**: Inherently brand-agnostic. They reference product attributes directly.
- **Current state**: No concept of "brand" exists anywhere in the codebase yet.

## Risks Identified
- The `FinishedGood` unique constraint will need updating to accommodate the new brand dimension.
- Heavy reliance on static methods in `ProductionService` and `StockService` might complicate brand injection if not handled carefully.
- The `FinishedGoodsResolver` has complex fallback logic which must be preserved or carefully adapted.

## Files Expected to Change
- `database/migrations/2026_08_14_000001_create_brands_table.php` (New)
- `app/Models/Brand.php` (New)
- `app/Services/BrandContextService.php` (New)
- `app/Helpers/helpers.php` (Update)
- `app/Http/Controllers/Shared/BrandController.php` (New)
- `database/seeders/BrandSeeder.php` (New)
- `routes/web.php` (Update)
- `database/seeders/DatabaseSeeder.php` (Update)
- `resources/views/layouts/app.blade.php` (Update)

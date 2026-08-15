# Raw Material Brand

## Database change
- Added `2026_08_15_000001_add_brand_id_to_raw_materials_table.php`.
- The migration adds nullable `raw_materials.brand_id` with a foreign key to `brands`.
- When existing raw-material rows are present, it resolves Solcon by the stable `SOL` brand code and backfills every null `brand_id` without altering stock fields.
- The column remains nullable for migration safety and existing non-admin creation paths; admin create and update require an active brand.

## Admin behavior
- Create loads active brands and selects Solcon by default.
- Edit loads active brands and selects the raw material's saved brand.
- The index eager-loads and displays each raw material's brand name.

## Files changed
- `database/migrations/2026_08_15_000001_add_brand_id_to_raw_materials_table.php`
- `app/Models/RawMaterial.php`
- `app/Http/Controllers/Admin/RawMaterialController.php`
- `app/Http/Requests/Admin/StoreRawMaterialRequest.php`
- `app/Http/Requests/Admin/UpdateRawMaterialRequest.php`
- `resources/views/admin/raw_materials/_form.blade.php`
- `resources/views/admin/raw_materials/index.blade.php`
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/TestFixtureSeeder.php`
- `tests/Feature/RawMaterialsTest.php`

## Verification
- Added focused coverage for the backfill, Solcon create default, Fixora create, brand edit/update, index display, and stock preservation.
- Run `php artisan test` and `php artisan migrate:status` after applying the change.

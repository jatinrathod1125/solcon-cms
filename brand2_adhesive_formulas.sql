-- =====================================================================
-- Brand 2 (Fixora) Tile Adhesive (TAD) Formulas & Packing Materials SQL
-- Grades: FX-01, FX-02 (F-107 recipe), FX-03 (F-121 recipe), FX-04 (F-133 recipe), FX-05 (F-147 recipe)
-- Generated on: 2026-08-26 13:54:51
-- =====================================================================

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. Insert Packing Materials (Bags) for Brand 2 (Fixora) if not exist
-- ---------------------------------------------------------------------
-- 'FX-01 BAG' already exists with ID 63
INSERT INTO `packing_materials` (`brand_id`, `category_id`, `name`, `code`, `size`, `unit_id`, `minimum_stock`, `opening_stock`, `current_stock`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'FX-02 BAG', 'F2B', '20KG', 1, 5000.0000, 5000.0000, 5000.0000, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'FX-02 BAG', `updated_at` = NOW();

INSERT INTO `packing_materials` (`brand_id`, `category_id`, `name`, `code`, `size`, `unit_id`, `minimum_stock`, `opening_stock`, `current_stock`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'FX-03 BAG', 'F3B', '20KG', 1, 5000.0000, 5000.0000, 5000.0000, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'FX-03 BAG', `updated_at` = NOW();

INSERT INTO `packing_materials` (`brand_id`, `category_id`, `name`, `code`, `size`, `unit_id`, `minimum_stock`, `opening_stock`, `current_stock`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'FX-04 BAG', 'F4B', '20KG', 1, 5000.0000, 5000.0000, 5000.0000, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'FX-04 BAG', `updated_at` = NOW();

INSERT INTO `packing_materials` (`brand_id`, `category_id`, `name`, `code`, `size`, `unit_id`, `minimum_stock`, `opening_stock`, `current_stock`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'FX-05 BAG', 'F5B', '20KG', 1, 5000.0000, 5000.0000, 5000.0000, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'FX-05 BAG', `updated_at` = NOW();


-- ---------------------------------------------------------------------
-- 2. Insert Formulas and Formula Items for FX-01 to FX-05
-- ---------------------------------------------------------------------

-- =====================================================================
-- Grade: FX-01 (Code: FX-01, ID: 9) <- Based on F-101
-- =====================================================================
DELETE FROM `formulas` WHERE `grade_id` = 9;
INSERT INTO `formulas` (`grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(9, 1, 'Recipe based on F-101', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `formula_items` (`formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 'packing', (SELECT `id` FROM `packing_materials` WHERE `brand_id` = 2 AND `code` = 'F1B' LIMIT 1), 1.0000, 3, 'output', 1.0000, 1, NOW(), NOW()),
(@formula_id, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, NOW(), NOW()),
(@formula_id, 13, 'raw', NULL, 500.0000, 1, 'formula', 1.0000, 3, NOW(), NOW()),
(@formula_id, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, NOW(), NOW()),
(@formula_id, 17, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 5, NOW(), NOW()),
(@formula_id, 18, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 6, NOW(), NOW());

-- =====================================================================
-- Grade: FX-02 (Code: FX-02, ID: 10) <- Based on F-107
-- =====================================================================
DELETE FROM `formulas` WHERE `grade_id` = 10;
INSERT INTO `formulas` (`grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(10, 1, 'Recipe based on F-107', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `formula_items` (`formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 'packing', (SELECT `id` FROM `packing_materials` WHERE `brand_id` = 2 AND `code` = 'F2B' LIMIT 1), 1.0000, 3, 'output', 1.0000, 1, NOW(), NOW()),
(@formula_id, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, NOW(), NOW()),
(@formula_id, 13, 'raw', NULL, 600.0000, 1, 'formula', 1.0000, 3, NOW(), NOW()),
(@formula_id, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, NOW(), NOW()),
(@formula_id, 17, 'raw', NULL, 10.0000, 1, 'formula', 1.0000, 5, NOW(), NOW()),
(@formula_id, 18, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 6, NOW(), NOW());

-- =====================================================================
-- Grade: FX-03 (Code: FX-03, ID: 11) <- Based on F-121
-- =====================================================================
DELETE FROM `formulas` WHERE `grade_id` = 11;
INSERT INTO `formulas` (`grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(11, 1, 'Recipe based on F-121', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `formula_items` (`formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 'packing', (SELECT `id` FROM `packing_materials` WHERE `brand_id` = 2 AND `code` = 'F3B' LIMIT 1), 1.0000, 3, 'output', 1.0000, 1, NOW(), NOW()),
(@formula_id, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, NOW(), NOW()),
(@formula_id, 13, 'raw', NULL, 700.0000, 1, 'formula', 1.0000, 3, NOW(), NOW()),
(@formula_id, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, NOW(), NOW()),
(@formula_id, 17, 'raw', NULL, 28.0000, 1, 'formula', 1.0000, 5, NOW(), NOW()),
(@formula_id, 18, 'raw', NULL, 6.0000, 1, 'formula', 1.0000, 6, NOW(), NOW()),
(@formula_id, 19, 'raw', NULL, 2.0000, 1, 'formula', 1.0000, 7, NOW(), NOW());

-- =====================================================================
-- Grade: FX-04 (Code: FX-04, ID: 12) <- Based on F-133
-- =====================================================================
DELETE FROM `formulas` WHERE `grade_id` = 12;
INSERT INTO `formulas` (`grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(12, 1, 'Recipe based on F-133', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `formula_items` (`formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(@formula_id, 12, 'raw', NULL, 320.0000, 1, 'formula', 1.0000, 1, NOW(), NOW()),
(@formula_id, 14, 'raw', NULL, 175.0000, 1, 'formula', 1.0000, 2, NOW(), NOW()),
(@formula_id, 17, 'raw', NULL, 7.0000, 1, 'formula', 1.0000, 3, NOW(), NOW()),
(@formula_id, 18, 'raw', NULL, 1.5000, 1, 'formula', 1.0000, 4, NOW(), NOW()),
(@formula_id, 15, 'raw', NULL, 50.0000, 1, 'formula', 1.0000, 5, NOW(), NOW()),
(@formula_id, 19, 'raw', NULL, 0.5000, 1, 'formula', 1.0000, 6, NOW(), NOW()),
(@formula_id, NULL, 'packing', (SELECT `id` FROM `packing_materials` WHERE `brand_id` = 2 AND `code` = 'F4B' LIMIT 1), 1.0000, 3, 'output', 1.0000, 7, NOW(), NOW());

-- =====================================================================
-- Grade: FX-05 (Code: FX-05, ID: 13) <- Based on F-147G
-- =====================================================================
DELETE FROM `formulas` WHERE `grade_id` = 13;
INSERT INTO `formulas` (`grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(13, 1, 'Recipe based on F-147G', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `formula_items` (`formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 'packing', (SELECT `id` FROM `packing_materials` WHERE `brand_id` = 2 AND `code` = 'F5B' LIMIT 1), 1.0000, 3, 'output', 1.0000, 1, NOW(), NOW()),
(@formula_id, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, NOW(), NOW()),
(@formula_id, 13, 'raw', NULL, 700.0000, 1, 'formula', 1.0000, 3, NOW(), NOW()),
(@formula_id, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, NOW(), NOW()),
(@formula_id, 17, 'raw', NULL, 60.0000, 1, 'formula', 1.0000, 5, NOW(), NOW()),
(@formula_id, 20, 'raw', NULL, 36.0000, 1, 'formula', 1.0000, 6, NOW(), NOW()),
(@formula_id, 18, 'raw', NULL, 7.0000, 1, 'formula', 1.0000, 7, NOW(), NOW()),
(@formula_id, 21, 'raw', NULL, 1.2000, 1, 'formula', 1.0000, 8, NOW(), NOW());

COMMIT;

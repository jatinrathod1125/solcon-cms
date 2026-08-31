-- =====================================================================
-- Brand 2 (Fixora) Epoxy Component Formulas and Items SQL Import
-- Database Table: epoxy_component_formulas & epoxy_component_formula_items
-- Generated on: 2026-08-26 14:14:30
-- =====================================================================

START TRANSACTION;

-- Component: 700gm Black Filler Pouch (Code: EPX-BLK-B2, ID: 76)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 76;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (76, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW()),
(@formula_id, 44, NULL, 0.7000, 1, NOW(), NOW());

-- Component: 700gm White Filler Pouch (Code: EPX-WHT-B2, ID: 77)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 77;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (77, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 46, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Mocha Filler Pouch (Code: EPX-MOC-B2, ID: 78)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 78;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (78, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 54, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Sterling Silver Filler Pouch (Code: EPX-STS-B2, ID: 79)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 79;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (79, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 55, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Hemp Filler Pouch (Code: EPX-HEM-B2, ID: 80)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 80;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (80, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 56, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Marble Beige Filler Pouch (Code: EPX-MBG-B2, ID: 81)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 81;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (81, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 57, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Sauterne Filler Pouch (Code: EPX-SAU-B2, ID: 82)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 82;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (82, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 58, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Smoke Grey Filler Pouch (Code: EPX-SMG-B2, ID: 83)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 83;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (83, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 59, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Silver Shadow Filler Pouch (Code: EPX-SSH-B2, ID: 84)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 84;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (84, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 60, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Slate Grey Filler Pouch (Code: EPX-SLG-B2, ID: 85)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 85;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (85, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 61, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Natural Grey Filler Pouch (Code: EPX-NGY-B2, ID: 86)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 86;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (86, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 62, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Platinum Filler Pouch (Code: EPX-PLT-B2, ID: 87)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 87;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (87, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 63, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Terracotta Filler Pouch (Code: EPX-TER-B2, ID: 88)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 88;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (88, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 64, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Satillo Filler Pouch (Code: EPX-SAT-B2, ID: 89)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 89;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (89, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 65, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Cadmium Red Filler Pouch (Code: EPX-CDR-B2, ID: 90)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 90;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (90, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 66, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Orange Filler Pouch (Code: EPX-ORG-B2, ID: 91)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 91;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (91, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 67, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Light Grey Filler Pouch (Code: EPX-LGY-B2, ID: 92)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 92;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (92, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 68, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Inca Gold Filler Pouch (Code: EPX-IGD-B2, ID: 93)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 93;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (93, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 69, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Blue Filler Pouch (Code: EPX-BLU-B2, ID: 94)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 94;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (94, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 70, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Ivy Filler Pouch (Code: EPX-IVY-B2, ID: 95)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 95;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (95, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 71, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Light Green Filler Pouch (Code: EPX-LGN-B2, ID: 96)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 96;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (96, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 72, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Sky Blue Filler Pouch (Code: EPX-SKB-B2, ID: 97)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 97;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (97, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 73, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Violet Filler Pouch (Code: EPX-VIO-B2, ID: 98)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 98;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (98, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 74, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Buff Filler Pouch (Code: EPX-BUF-B2, ID: 99)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 99;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (99, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 51, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Coffee Brown Filler Pouch (Code: EPX-CBR-B2, ID: 100)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 100;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (100, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 52, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Chocolate Brown Filler Pouch (Code: EPX-CHB-B2, ID: 101)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 101;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (101, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 53, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Ivory Filler Pouch (Code: EPX-IVR-B2, ID: 102)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 102;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (102, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 47, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Parchment Filler Pouch (Code: EPX-PAR-B2, ID: 103)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 103;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (103, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 48, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Jaisalmer Filler Pouch (Code: EPX-JAI-B2, ID: 104)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 104;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (104, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 49, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: 700gm Dusty Rose Filler Pouch (Code: EPX-DTR-B2, ID: 105)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 105;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (105, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 50, NULL, 0.7000, 1, NOW(), NOW()),
(@formula_id, NULL, 67, 1.0000, 3, NOW(), NOW());

-- Component: Jari Powder - Silver (Code: EPX-JARI-SLV-B2, ID: 106)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 106;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (106, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 87, NULL, 1.0000, 1, NOW(), NOW()),
(@formula_id, NULL, 105, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 76, 20.0000, 3, NOW(), NOW());

-- Component: Jari Powder - Copper (Code: EPX-JARI-CPR-B2, ID: 107)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 107;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (107, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 90, NULL, 1.0000, 1, NOW(), NOW()),
(@formula_id, NULL, 76, 20.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 105, 1.0000, 3, NOW(), NOW());

-- Component: Jari Powder - Gold (Code: EPX-JARI-GLD-B2, ID: 108)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 108;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (108, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 88, NULL, 1.0000, 1, NOW(), NOW()),
(@formula_id, NULL, 105, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 76, 20.0000, 3, NOW(), NOW());

-- Component: Jari Powder - Red (Code: EPX-JARI-RED-B2, ID: 109)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 109;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (109, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 89, NULL, 1.0000, 1, NOW(), NOW()),
(@formula_id, NULL, 105, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 76, 20.0000, 3, NOW(), NOW());

-- Component: SB+ 1 KG (Code: EPX-SBP-1-B2, ID: 110)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 110;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (110, 1, 1, 'Formula for SB+ 1 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SB+ 5 KG (Code: EPX-SBP-5-B2, ID: 111)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 111;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (111, 1, 1, 'Formula for SB+ 5 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SB+ 20 KG (Code: EPX-SBP-20-B2, ID: 112)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 112;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (112, 1, 1, 'Formula for SB+ 20 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SB++ 1 KG (Code: EPX-SBPP-1-B2, ID: 113)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 113;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (113, 1, 1, 'Formula for SB++ 1 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SB++ 5 KG (Code: EPX-SBPP-5-B2, ID: 114)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 114;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (114, 1, 1, 'Formula for SB++ 5 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SB++ 20 KG (Code: EPX-SBPP-20-B2, ID: 115)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 115;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (115, 1, 1, 'Formula for SB++ 20 KG', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SK+ 1 LTR (Code: EPX-SKP-1-B2, ID: 116)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 116;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (116, 1, 1, 'Formula for SK+ 1 LTR', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SK+ 5 LTR (Code: EPX-SKP-5-B2, ID: 117)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 117;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (117, 1, 1, 'Formula for SK+ 5 LTR', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: SK+ 20 LTR (Code: EPX-SKP-20-B2, ID: 118)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 118;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (118, 1, 1, 'Formula for SK+ 20 LTR', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW());

-- Component: 100 GM HARDNER BOTTLE (Code: EPX-BLT-01-B2, ID: 119)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 119;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (119, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 79, 1.0000, 3, NOW(), NOW()),
(@formula_id, 81, NULL, 0.1000, 1, NOW(), NOW());

-- Component: 200GM RESIN BOTTLE (Code: F-011-B2, ID: 120)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 120;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (120, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 0.2000, 1, NOW(), NOW()),
(@formula_id, NULL, 80, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 88, 1.0000, 3, NOW(), NOW());

-- Component: CLIP 2MM (Code: 2MM-B2, ID: 121)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 121;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (121, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 72, 25.0000, 3, NOW(), NOW());

-- Component: CLIP 3MM (Code: 3MM-B2, ID: 122)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 122;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (122, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 73, 25.0000, 3, NOW(), NOW());

-- Component: CLIP 4MM (Code: 4MM-B2, ID: 123)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 123;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (123, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 74, 25.0000, 3, NOW(), NOW());

-- Component: SPACER 2MM (Code: EPX-SP-2MM-B2, ID: 124)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 124;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (124, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 68, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 1, NOW(), NOW());

-- Component: SPACER 3MM (Code: EPX-SP-3MM-B2, ID: 125)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 125;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (125, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 69, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 1, NOW(), NOW());

-- Component: SPACER 4MM (Code: EPX-SP-4MM-B2, ID: 126)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 126;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (126, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 70, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 1, NOW(), NOW());

-- Component: SPACER 5MM (Code: EPX-SP-5MM-B2, ID: 127)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 127;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (127, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 71, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW());

-- Component: JACK LEVELLING (Code: JL-01-B2, ID: 128)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 128;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (128, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 115, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 113, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 114, 1.0000, 3, NOW(), NOW());

-- Component: PLASTIC BOX (Code: PB-01-B2, ID: 129)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 129;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (129, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 114, 1.0000, 3, NOW(), NOW());

-- Component: SPACER 6MM (Code: EPX-SP-6MM-B2, ID: 130)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 130;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (130, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 117, 50.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW());

-- Component: WEDGE (Code: EPX-WEDGE-B2, ID: 134)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 134;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (134, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 75, 25.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 104, 1.0000, 3, NOW(), NOW());

-- Component: PLIER (Code: EPX-PLIER-B2, ID: 136)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 136;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (136, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 118, 1.0000, 3, NOW(), NOW());

-- Component: 500 GM HARDNER BOTTLE (Code: HRD-500-B2, ID: 137)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 137;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (137, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 81, NULL, 0.5000, 1, NOW(), NOW()),
(@formula_id, NULL, 81, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 89, 1.0000, 3, NOW(), NOW());

-- Component: 1 KG RESIN BOTTLE (Code: REN-1K-B2, ID: 138)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 138;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (138, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, 80, NULL, 1.0000, 1, NOW(), NOW()),
(@formula_id, NULL, 82, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 90, 1.0000, 1, NOW(), NOW());

-- Component: Grout Admix 200GM (Code: EPX-GA-200GM-B2, ID: 139)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 139;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (139, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 80, 35.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 93, 35.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 97, 1.0000, 3, NOW(), NOW());

-- Component: Tiles Cleaner 1-LTR (Code: EPX-TC-1LTR-B2, ID: 140)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 140;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (140, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 99, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 91, 1.0000, 1, NOW(), NOW());

-- Component: Tiles Cleaner 5-LTR (Code: EPX-TC-5LTR-B2, ID: 141)
DELETE FROM `epoxy_component_formulas` WHERE `epoxy_component_id` = 141;
INSERT INTO `epoxy_component_formulas` (`epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (141, 1, 1, 'Standard Recipe', 1, 1, NOW(), NOW());
SET @formula_id = LAST_INSERT_ID();
INSERT INTO `epoxy_component_formula_items` (`epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(@formula_id, NULL, 100, 1.0000, 3, NOW(), NOW()),
(@formula_id, NULL, 92, 4.0000, 3, NOW(), NOW());

COMMIT;

-- =====================================================================
-- SOLITITE Epoxy Components SQL Import (Brand 1 & Brand 2)
-- Table: epoxy_components
-- Sizes: 1.8KG, 900GM, 450GM
-- Generated on: 2026-08-26
-- =====================================================================

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. Brand 1 (Solcon) Solitite Components
-- ---------------------------------------------------------------------
INSERT INTO `epoxy_components` (`brand_id`, `name`, `code`, `category`, `purpose`, `unit_id`, `is_active`, `created_at`, `updated_at`)
VALUES
(1, 'SOLITITE 1.8KG', 'EPX-SOL-1.8KG', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(1, 'SOLITITE 900GM', 'EPX-SOL-900GM', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(1, 'SOLITITE 450GM', 'EPX-SOL-450GM', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
`name` = VALUES(`name`),
`category` = VALUES(`category`),
`purpose` = VALUES(`purpose`),
`unit_id` = VALUES(`unit_id`),
`is_active` = VALUES(`is_active`),
`updated_at` = NOW();

-- ---------------------------------------------------------------------
-- 2. Brand 2 (Fixora) Solitite Components
-- ---------------------------------------------------------------------
INSERT INTO `epoxy_components` (`brand_id`, `name`, `code`, `category`, `purpose`, `unit_id`, `is_active`, `created_at`, `updated_at`)
VALUES
(2, 'SOLITITE 1.8KG', 'EPX-SOL-1.8KG-B2', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(2, 'SOLITITE 900GM', 'EPX-SOL-900GM-B2', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(2, 'SOLITITE 450GM', 'EPX-SOL-450GM-B2', 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
`name` = VALUES(`name`),
`category` = VALUES(`category`),
`purpose` = VALUES(`purpose`),
`unit_id` = VALUES(`unit_id`),
`is_active` = VALUES(`is_active`),
`updated_at` = NOW();

COMMIT;

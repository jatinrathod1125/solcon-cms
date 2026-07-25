-- -------------------------------------------------------------
-- SOLCON CMS - EPOXY DEPARTMENT DYNAMIC ORDER FLOW DB UPDATES
-- Run this SQL on your database to apply the schema updates
-- -------------------------------------------------------------

-- 1. Add dynamic epoxy columns to marketing_order_items if not present
ALTER TABLE `marketing_order_items` 
ADD COLUMN IF NOT EXISTS `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `epoxy_component_id` bigint UNSIGNED DEFAULT NULL;

-- 2. Insert/Ensure missing Epoxy Components (Wedge, Vacuum if not present)
INSERT INTO `epoxy_components` (`id`, `name`, `code`, `requires_color`, `category`, `purpose`, `unit_id`, `is_active`, `created_at`, `updated_at`) VALUES
(46, 'CLIP 2MM', '2MM', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(47, 'CLIP 3MM', '3MM', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(48, 'CLIP 4MM', '4MM', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(49, 'WEDGE', 'EPX-WDG', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(50, 'SPACER 2MM', 'EPX-SP-2MM', 0, 'Box', 'Direct Finished Product', 1, 1, NOW(), NOW()),
(51, 'SPACER 3MM', 'EPX-SP-3MM', 0, 'Box', 'Direct Finished Product', 1, 1, NOW(), NOW()),
(52, 'SPACER 4MM', 'EPX-SP-4MM', 0, 'Box', 'Direct Finished Product', 1, 1, NOW(), NOW()),
(53, 'SPACER 5MM', 'EPX-SP-5MM', 0, 'Box', 'Direct Finished Product', 1, 1, NOW(), NOW()),
(60, 'JACK LEVELLING', 'JL-01', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(61, 'PLASTIC BOX', 'PB-01', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(62, 'VACUUM', 'EPX-VAC', 0, 'Box', 'Direct Finished Product', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `category`=VALUES(`category`), `is_active`=1;

-- 3. Initialize Inventory in finished_goods for components (Clips, Spacers, Levelers, Tools - Packing: Box)
-- Department ID 3 represents Epoxy department

-- Clips & Wedges & Levelers (components 46, 47, 48, 49, 60) - Unit: Box
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 46, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 47, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 48, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 49, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 60, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Spacers (components 50, 51, 52, 53) - Unit: Box
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 50, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 51, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 52, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 53, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Tools (Plastic Box, Vacuum, Plier, Trowel) - Unit: Box / Pcs
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 61, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 62, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Spacers & Levelers as Epoxy Product fallback stock if product_id 7 or 8 are queried:
INSERT INTO `epoxy_products` (`id`, `name`, `code`, `requires_color`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(7, 'SPACER', 'SP', 0, 1, 1, NOW(), NOW()),
(8, 'TILES LEVELER', 'TL', 0, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `is_active`=1;

INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 7, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

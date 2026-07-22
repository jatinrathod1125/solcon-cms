-- -------------------------------------------------------------
-- SOLCON CMS - EPOXY DEPARTMENT DYNAMIC ORDER FLOW DB UPDATES
-- Run this SQL on your database to apply the schema updates
-- -------------------------------------------------------------

-- 1. Add dynamic epoxy columns to marketing_order_items if not present
ALTER TABLE `marketing_order_items` 
ADD COLUMN `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
ADD COLUMN `epoxy_component_id` bigint UNSIGNED DEFAULT NULL;

-- 2. Add foreign keys for the new columns
ALTER TABLE `marketing_order_items`
ADD CONSTRAINT `fk_mkt_items_epoxy_filler_color` FOREIGN KEY (`epoxy_filler_color_id`) REFERENCES `epoxy_filler_colors` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `fk_mkt_items_epoxy_component` FOREIGN KEY (`epoxy_component_id`) REFERENCES `epoxy_components` (`id`) ON DELETE SET NULL;

-- 3. Insert new Epoxy Products (requires_color = 0, is_active = 1)
-- department_id for Epoxy is 3. Solitite, Tiles Cleaner, Grout Admix, Spacer, Tiles Leveler
INSERT INTO `epoxy_products` (`id`, `name`, `code`, `requires_color`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'SOLITITE', 'SOL', 0, 1, 1, NOW(), NOW()),
(5, 'TILES CLEANER', 'TC', 0, 1, 1, NOW(), NOW()),
(6, 'GROUT ADMIX', 'GA', 0, 1, 1, NOW(), NOW()),
(7, 'SPACER', 'SP', 0, 1, 1, NOW(), NOW()),
(8, 'TILES LEVELER', 'TL', 0, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `code`=VALUES(`code`), `requires_color`=VALUES(`requires_color`);

-- 4. Insert new Epoxy Components (Jari Powder colors, SB+, SB++, SK+)
-- Category classifications: Powder/Liquid
-- Unit ID 3 represents 'PCS'
INSERT INTO `epoxy_components` (`id`, `name`, `code`, `requires_color`, `category`, `purpose`, `unit_id`, `is_active`, `created_at`, `updated_at`) VALUES
(31, 'Jari Powder - Silver', 'EPX-JARI-SLV', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(32, 'Jari Powder - Copper', 'EPX-JARI-CPR', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(33, 'Jari Powder - Gold', 'EPX-JARI-GLD', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(34, 'Jari Powder - Red', 'EPX-JARI-RED', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(35, 'SB+ 1 KG', 'EPX-SBP-1', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(36, 'SB+ 5 KG', 'EPX-SBP-5', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(37, 'SB+ 20 KG', 'EPX-SBP-20', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(38, 'SB++ 1 KG', 'EPX-SBPP-1', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(39, 'SB++ 5 KG', 'EPX-SBPP-5', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(40, 'SB++ 20 KG', 'EPX-SBPP-20', 0, 'Powder', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(41, 'SK+ 1 LTR', 'EPX-SKP-1', 0, 'Liquid', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(42, 'SK+ 5 LTR', 'EPX-SKP-5', 0, 'Liquid', 'Direct Finished Product', 3, 1, NOW(), NOW()),
(43, 'SK+ 20 LTR', 'EPX-SKP-20', 0, 'Liquid', 'Direct Finished Product', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `code`=VALUES(`code`), `requires_color`=VALUES(`requires_color`);

-- 5. Initialize Inventory in finished_goods with 100 available units for testing
-- Department ID 3 represents Epoxy department

-- Solitite (product 4)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 4, '1.8KG', 100, 180.0000, 10, 'active', NOW(), NOW()),
(3, 4, '900 GM', 100, 90.0000, 10, 'active', NOW(), NOW()),
(3, 4, '450 GM', 100, 45.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Resin Kit (product 3)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 3, '0.3KG', 100, 30.0000, 10, 'active', NOW(), NOW()),
(3, 3, '1.5KG', 100, 150.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Tiles Cleaner (product 5)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 5, '1-LTR', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 5, '5-LTR', 100, 500.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Grout Admix (product 6)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 6, '200GM', 100, 20.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Spacers (product 7)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 7, '2MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 7, '3MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 7, '4MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 7, '5MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 7, '6MM', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Tiles Levelers (product 8)
INSERT INTO `finished_goods` (`department_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 8, 'CLIP 2MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'CLIP 3MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'CLIP 4MM', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'WEDGE', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'LEVELLING JACK SPACER', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'TROWEL', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'PLIER', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 8, 'VACUUM', 100, 100.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Components Jari Powder (components 31-34, packing 'Pckt')
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 31, 'Pckt', 100, 10.0000, 10, 'active', NOW(), NOW()),
(3, 32, 'Pckt', 100, 10.0000, 10, 'active', NOW(), NOW()),
(3, 33, 'Pckt', 100, 10.0000, 10, 'active', NOW(), NOW()),
(3, 34, 'Pckt', 100, 10.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Components SB+ (components 35-37, packing 'Box')
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 35, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 36, 'Box', 100, 500.0000, 10, 'active', NOW(), NOW()),
(3, 37, 'Box', 100, 2000.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Components SB++ (components 38-40, packing 'Box')
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 38, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 39, 'Box', 100, 500.0000, 10, 'active', NOW(), NOW()),
(3, 40, 'Box', 100, 2000.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

-- Components SK+ (components 41-43, packing 'Box')
INSERT INTO `finished_goods` (`department_id`, `epoxy_component_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(3, 41, 'Box', 100, 100.0000, 10, 'active', NOW(), NOW()),
(3, 42, 'Box', 100, 500.0000, 10, 'active', NOW(), NOW()),
(3, 43, 'Box', 100, 2000.0000, 10, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `available_bags`=VALUES(`available_bags`);

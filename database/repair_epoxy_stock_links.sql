-- Repair the legacy Epoxy order identity mismatch.
--
-- Backup the database before running this file. Run the SELECT statements first
-- and verify that product IDs 20 (Tiles Cleaner) and 21 (Grout Admix) match your
-- database. If they differ, replace only those two numbers in the UPDATE clauses.

-- 1. Audit the rows that will be changed.
SELECT
    moi.id,
    mo.order_number,
    moi.epoxy_product_id,
    ep.code AS epoxy_product_code,
    moi.epoxy_component_id,
    moi.packing,
    moi.quantity_bags
FROM marketing_order_items AS moi
JOIN marketing_orders AS mo ON mo.id = moi.marketing_order_id
LEFT JOIN epoxy_products AS ep ON ep.id = moi.epoxy_product_id
WHERE moi.department_code = 'EPX'
  AND moi.epoxy_component_id IS NULL
  AND UPPER(REPLACE(REPLACE(moi.packing, '-', ''), ' ', '')) IN ('200GM', '1LTR', '5LTR')
ORDER BY moi.id;

-- 2. Confirm the target component IDs and their Finished Goods stock.
SELECT
    ec.id AS component_id,
    ec.code,
    ec.name,
    ec.purpose,
    fg.id AS finished_good_id,
    fg.packing AS finished_good_packing,
    fg.available_bags
FROM epoxy_components AS ec
LEFT JOIN finished_goods AS fg ON fg.epoxy_component_id = ec.id
WHERE ec.code IN ('EPX-GA-200GM', 'EPX-TC-1LTR', 'EPX-TC-5LTR')
ORDER BY ec.code, fg.id;

START TRANSACTION;

-- 3. These three are direct finished products, so preparation must increase
-- Finished Goods instead of raw-material/component stock.
UPDATE epoxy_components
SET purpose = 'Direct Finished Product'
WHERE code IN ('EPX-GA-200GM', 'EPX-TC-1LTR', 'EPX-TC-5LTR')
  AND purpose <> 'Direct Finished Product';

-- 4. Grout Admix: map legacy product orders to the component-backed SKU.
UPDATE marketing_order_items AS moi
JOIN epoxy_components AS ec ON ec.code = 'EPX-GA-200GM'
LEFT JOIN epoxy_products AS ep ON ep.id = moi.epoxy_product_id
SET moi.epoxy_component_id = ec.id,
    moi.epoxy_product_id = NULL
WHERE moi.department_code = 'EPX'
  AND moi.epoxy_component_id IS NULL
  AND UPPER(REPLACE(REPLACE(moi.packing, '-', ''), ' ', '')) = '200GM'
  AND (UPPER(ep.code) = 'GA' OR moi.epoxy_product_id = 21);

-- 5. Tiles Cleaner: map each packing size to its exact component-backed SKU.
UPDATE marketing_order_items AS moi
JOIN epoxy_components AS ec ON ec.code = 'EPX-TC-1LTR'
LEFT JOIN epoxy_products AS ep ON ep.id = moi.epoxy_product_id
SET moi.epoxy_component_id = ec.id,
    moi.epoxy_product_id = NULL
WHERE moi.department_code = 'EPX'
  AND moi.epoxy_component_id IS NULL
  AND UPPER(REPLACE(REPLACE(moi.packing, '-', ''), ' ', '')) = '1LTR'
  AND (UPPER(ep.code) = 'TC' OR moi.epoxy_product_id = 20);

UPDATE marketing_order_items AS moi
JOIN epoxy_components AS ec ON ec.code = 'EPX-TC-5LTR'
LEFT JOIN epoxy_products AS ep ON ep.id = moi.epoxy_product_id
SET moi.epoxy_component_id = ec.id,
    moi.epoxy_product_id = NULL
WHERE moi.department_code = 'EPX'
  AND moi.epoxy_component_id IS NULL
  AND UPPER(REPLACE(REPLACE(moi.packing, '-', ''), ' ', '')) = '5LTR'
  AND (UPPER(ep.code) = 'TC' OR moi.epoxy_product_id = 20);

-- 6. Keep already-created dispatch items aligned with their repaired order item.
UPDATE dispatch_items AS di
JOIN marketing_order_items AS moi ON moi.id = di.marketing_order_item_id
SET di.epoxy_component_id = moi.epoxy_component_id,
    di.epoxy_product_id = NULL
WHERE moi.department_code = 'EPX'
  AND moi.epoxy_component_id IS NOT NULL
  AND moi.packing IN ('200GM', '1-LTR', '5-LTR');

-- Review the changed rows. Run COMMIT only when these are correct.
SELECT
    moi.id,
    mo.order_number,
    ec.code AS component_code,
    moi.packing,
    moi.quantity_bags
FROM marketing_order_items AS moi
JOIN marketing_orders AS mo ON mo.id = moi.marketing_order_id
LEFT JOIN epoxy_components AS ec ON ec.id = moi.epoxy_component_id
WHERE moi.department_code = 'EPX'
  AND moi.packing IN ('200GM', '1-LTR', '5-LTR')
ORDER BY moi.id;

COMMIT;

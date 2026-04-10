INSERT INTO `auto_processes` (`auto_process_id`, `auto_process_name`, `action_type`, `action_column_name`, `action_value`, `condition_match_type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, '佐川急便', 'shipping_method_update', 'shipping_method_id', '4', 'any', 1, 1, '2026-03-17 00:59:07', '2026-03-17 00:59:07'),
(2, 'EMS', 'shipping_method_update', 'shipping_method_id', '5', 'any', 1, 2, '2026-03-17 00:59:47', '2026-03-17 00:59:47'),
(3, 'ネコポス', 'shipping_method_update', 'shipping_method_id', '1', 'any', 1, 3, '2026-03-17 01:00:08', '2026-03-17 01:00:08'),
(4, 'UPS', 'shipping_method_update', 'shipping_method_id', '6', 'any', 1, 4, '2026-03-17 01:00:08', '2026-03-17 01:00:08'),
(5, '沖縄宛て配送方法変更', 'shipping_method_update', 'shipping_method_id', '3', 'all', 1, 5, '2026-04-06 04:16:22', '2026-04-06 04:16:22');

INSERT INTO `auto_process_conditions` (`auto_process_condition_id`, `auto_process_id`, `column_name`, `operator`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'mall_shipping_method', '=', '佐川急便', '2026-03-17 01:00:21', '2026-03-17 01:00:21'),
(2, 1, 'mall_shipping_method', '=', '宅急便', '2026-03-17 01:00:21', '2026-03-17 01:00:21'),
(3, 3, 'mall_shipping_method', '=', 'ゆうパケット', '2026-03-17 01:00:31', '2026-03-17 01:00:31'),
(4, 3, 'mall_shipping_method', '=', 'メール便', '2026-03-17 01:00:31', '2026-03-17 01:00:31'),
(5, 3, 'mall_shipping_method', '=', '通常配送', '2026-03-17 01:00:31', '2026-03-17 01:00:31'),
(6, 2, 'mall_shipping_method', '=', 'Standard EMS', '2026-03-17 01:01:09', '2026-03-17 01:01:09'),
(7, 2, 'mall_shipping_method', '=', 'Standard Shipping', '2026-03-17 01:01:09', '2026-03-17 01:01:09'),
(8, 2, 'mall_shipping_method', '=', 'ReShip', '2026-03-17 01:01:09', '2026-03-17 01:01:09'),
(9, 4, 'mall_shipping_method', '=', 'UPS ( Fastest )', '2026-03-17 01:01:09', '2026-03-17 01:01:09'),
(10, 4, 'mall_shipping_method', '=', 'UPS', '2026-03-17 01:01:09', '2026-03-17 01:01:09'),
(11, 5, 'shipping_method_id', '=', '4', '2026-04-06 04:16:45', '2026-04-06 04:16:45'),
(12, 5, 'ship_province_name', '=', '沖縄県', '2026-04-06 04:16:45', '2026-04-06 04:16:45');
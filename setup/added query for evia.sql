ALTER TABLE `trans_sales` ADD `customer_name` VARCHAR( 255 ) NULL AFTER `billed`;

ALTER TABLE `trans_sales_menus` ADD `free_reason` VARCHAR( 255 ) NULL AFTER `free_user_id`;
ALTER TABLE `users` ADD `branch_code` VARCHAR( 255 ) NULL AFTER `sync_id`;
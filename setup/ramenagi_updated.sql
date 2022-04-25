UPDATE menus SET `menu_name`="Birthday Promo",`menu_short_desc`="Birthday Promo" WHERE menu_code="PRO0004" AND menu_name='Free Butao';
ALTER TABLE `modifiers`
ADD COLUMN `mod_sub_cat_id`  int NULL AFTER `update_date`;
UPDATE modifiers SET `mod_sub_cat_id`="1" ;

<?php

/* 
 * 
 * @package products_profit_margin_calculator_report
 * @copyright Copyright 2003-2015 ZenCart.Codes a Pro-Webs Company
 * @copyright Copyright 2003-2015 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @filename 2_2_1.php
 * @file created 2015-04-21 11:49:24 AM
 * 
 */

global $sniffer;
if (!$sniffer->field_exists(TABLE_ORDERS, 'orders_ot_shipping'))  $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD `orders_ot_shipping` DECIMAL( 15, 4 ) DEFAULT '0.0000' NOT NULL AFTER `shipping_module_code` ;");

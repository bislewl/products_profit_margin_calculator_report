<?php

/* 
 * 
 * @package products_profit_margin_calculator_report
 * @copyright Copyright 2003-2015 ZenCart.Codes a Pro-Webs Company
 * @copyright Copyright 2003-2015 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @filename 2_2_0.php
 * @file created 2015-04-21 11:19:56 AM
 * 
 */

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description, set_function) VALUES (" . (int) $configuration_group_id . ", 'PROFIT_MARGIN_REPORT_OT_SHIPPING', 'Shipping Order Total Value', 'ot_shipping', 'This is the order_total value for shipping, ussually it is ot_shipping, so you really should not need to change it.', '');");
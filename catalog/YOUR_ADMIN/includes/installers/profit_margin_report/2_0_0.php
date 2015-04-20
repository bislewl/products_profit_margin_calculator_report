<?php
// use $configuration_group_id where needed

// For Admin Pages

$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
    $admin_page = 'configProfitMarginReport';
  // delete configuration menu
  $db->Execute("DELETE FROM ".TABLE_ADMIN_PAGES." WHERE page_key = '".$admin_page."' LIMIT 1;");
  // add configuration menu
  if (!zen_page_key_exists($admin_page)) {
    if ((int)$configuration_group_id > 0) {
      zen_register_admin_page($admin_page,
                              'BOX_CONFIG_PROFIT_MARGIN_REPORT', 
                              'FILENAME_CONFIGURATION',
                              'gID=' . $configuration_group_id, 
                              'configuration', 
                              'Y',
                              $configuration_group_id);
        
      $messageStack->add('Enabled Profit Margin Report Configuration Menu Item.', 'success');
    }
  }
      $admin_page = 'reportsProfitMarginReport';
  // delete configuration menu
  $db->Execute("DELETE FROM ".TABLE_ADMIN_PAGES." WHERE page_key = '".$admin_page."' LIMIT 1;");
  // add configuration menu
  if (!zen_page_key_exists($admin_page)) {
    if ((int)$configuration_group_id > 0) {
      zen_register_admin_page($admin_page,
                              'BOX_REPORTS_PROFIT_MARGIN_REPORT', 
                              'FILENAME_REPORTS_PROFIT_MARGIN',
                              '', 
                              'reports', 
                              'Y',
                              '11');
        
      $messageStack->add('Enabled Profit Margin Report Reports Menu Item.', 'success');
    }
  }
}

// If your checking for a field

global $sniffer;
if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_cost'))  $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD `products_cost` DECIMAL( 15, 4 ) DEFAULT '0.0000' NOT NULL AFTER `products_price` ;");
if (!$sniffer->field_exists(TABLE_ORDERS_PRODUCTS, 'products_cost'))  $db->Execute("ALTER TABLE " . TABLE_ORDERS_PRODUCTS . " ADD `products_cost` DECIMAL( 15, 4 ) DEFAULT '0.0000' NOT NULL AFTER `products_price` ;"); 

/*
 * For adding a configuration value
 * $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description, set_function) VALUES (" . (int) $configuration_group_id . ", 'CONFIGURATION_KEY', 'This a configuration value name', 'true', 'This is the description of the configuration value', 'zen_cfg_select_option(array(\'true\', \'false\'),');");
 */

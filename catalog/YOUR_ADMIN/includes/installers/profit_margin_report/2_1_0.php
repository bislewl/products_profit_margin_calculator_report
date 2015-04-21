<?php


$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description, set_function) VALUES (" . (int) $configuration_group_id . ", 'PROFIT_MARGIN_REPORT_NUM_ROWS', 'Number of Rows Per Page', '".PROFIT_MARGIN_REPORT_NUM_ROWS."', 'This is the number of rows that should be shown in the report', '');");

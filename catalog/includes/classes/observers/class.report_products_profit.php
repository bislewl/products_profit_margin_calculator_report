<?php
/**
 * @copyright Copyright 2010-2014  ZenCart.codes Owned & Operated by PRO-Webs, Inc. 
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
class reportProductsProfitPurchase extends base {

    function reportProductsProfitPurchase() {
        global $zco_notifier;
        $zco_notifier->attach($this, array('NOTIFY_HEADER_START_CHECKOUT_SUCCESS'));
    }

    function update(&$class, $eventID, $paramsArray) {
        global $db,$_SESSION;
        $rpp_orders_id = (int)$_SESSION['order_number_created'];
                    //get products in cart
        $rpp_orders_products = $db->Execute("SELECT * FROM ".TABLE_ORDERS_PRODUCTS." WHERE orders_id='".$rpp_orders_id."'");
        while(!$rpp_orders_products->EOF){
            $product_query = $db->Execute("SELECT * FROM ".TABLE_PRODUCTS." WHERE products_id='".$rpp_orders_products->fields['products_id']."'");
            $products_cost = $product_query->fields['products_cost'];
            $db->Execute("UPDATE ".TABLE_ORDERS_PRODUCTS." SET products_cost='".$products_cost."' WHERE orders_products_id='".$rpp_orders_products->fields['orders_products_id']."'");
            $rpp_orders_products->MoveNext();
        }
        $ot_shipping = $db->Execute("SELECT value FROM ".TABLE_ORDERS_TOTALS." WHERE orders_id = '".$rpp_orders_id."' AND class='ot_shipping'");
        $db->Execute("UPDATE ".TABLE_ORDERS." SET orders_ot_shipping='".$ot_shipping->fields['value']."' WHERE orders_id='".$rpp_orders_id."'");
    }
}

<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//  $Id: stats_products_purchased.php 2497 2005-12-02 01:48:55Z drbyte $
//

require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <script type="text/javascript">
            <!--
          function init()
            {
                cssjsmenu('navbar');
                if (document.getElementById)
                {
                    var kill = document.getElementById('hoverJS');
                    kill.disabled = true;
                }
            }
            // -->
        </script>
    </head>
    <body onload="init()">
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->

        <!-- body //-->

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>		

        <?php
//Since stats_products_margin.php never existed in the original package, I have removed the link to it. Once its back in place, you can take out the comments from these lines AND remove the php tags
        /* <ul id="tablist">
          <li><a href="stats_products_margin.php" class="current" onClick="return expandcontent('sc1', this)">Product Profit Report</a></li>
          </ul> */
        ?>

        <div id="tabcontentcontainer">

            <div id="sc1" class="tabcontent">
                <table border="0" width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                        <!-- body_text //-->
                        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                            <!--  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>-->
                                            <!--   <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>-->
                                            </tr>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <?php
                                            $where_statement = '';
                                            $from_string = '';
                                            $to_string = '';
                                            if (isset($_GET['from']) && isset($_GET['to'])) {
                                                $from_date = zen_db_prepare_input($_GET['from']);
                                                $to_date = zen_db_prepare_input($_GET['to']);
                                                $from_string = strtotime($from_date);
                                                $to_string = strtotime($to_date);

                                                $date_from = date('Y-m-d', $from_string);
                                                $date_to = date('Y-m-d', $to_string);
                                                $where_statement = ' WHERE ';
                                                $where_statement .= "  o.date_purchased >= '" . $date_from . "'  ";
                                                $where_statement .= " AND o.date_purchased <= '" . $date_to . "'  ";
                                            }
                                            ?>

                                            <tr>
                                                <td class="smallText" valign="top"></td>
                                                <td class="smallText" align="right">
                                                    <?php
                                                    echo '<b>Refine</b>';
                                                    echo zen_draw_form($name, FILENAME_REPORTS_PROFIT_MARGIN . '.php', '', 'get', 'zen_draw_input_field', 'true');
                                                    echo ' From: ' . zen_draw_input_field('from', $from_date);
                                                    echo ' To: ' . zen_draw_input_field('to', $to_date);
                                                    echo zen_draw_input_field('submit', 'Refine Results', '', false, 'submit');
                                                    echo '</form>';
                                                    ?>
                                                    &nbsp;</td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                        <tr class="dataTableHeadingRow">
                                                            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                                                            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PURCHASED; ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PURCHASED; ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_COST; ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_PROFIT; ?>&nbsp;</td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_SHIPPING; ?>&nbsp;</td>


                                                        </tr>
                                                        <?php
                                                        $max_page_results = PROFIT_MARGIN_REPORT_NUM_ROWS;
                                                        if (isset($_GET['page']) && ($_GET['page'] > 1))
                                                            $rows = $_GET['page'] * $max_page_results - $max_page_results;
// The new query uses real order info from the orders_products table, and is theoretically more accurate.
// To use this newer query, remove the "1" from the following line ($products_query_raw1 becomes $products_query_raw )

                                                        $products_query_raw = "SELECT op.products_id, op.products_name, SUM(op.products_quantity) AS products_ordered, SUM(op.products_cost * op.products_quantity) AS total_cost, sum(op.final_price * op.products_quantity) AS total_final_price, sum(o.orders_ot_shipping) AS ot_shipping FROM " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_PRODUCTS . " op ON (op.orders_id = o.orders_id) JOIN " . TABLE_ORDERS_TOTAL . " ot ON (o.orders_id = ot.orders_id) " . $where_statement . " GROUP BY products_id  ORDER BY products_ordered DESC, products_name";


                                                        $products_query_numrows = '';
                                                        $products_split = new splitPageResults($_GET['page'], $max_page_results, $products_query_raw, $products_query_numrows);

                                                        $rows = 0;
                                                        $products = $db->Execute($products_query_raw);
                                                        $product_qty_purchase = (int) 0;
                                                        $total_products_purchased = (float) 0.00;
                                                        $total_cost = (float) 0.00;
                                                        $total_profit = (float) 0.00;
                                                        $total_shipping = (float)0.00;

                                                        while (!$products->EOF) {
                                                            $rows++;

                                                            if (strlen($rows) < 2) {
                                                                $rows = '0' . $rows;
                                                            }
                                                            $cPath = zen_get_product_path($products->fields['products_id']);
                                                            $products_profit = $products->fields['total_final_price'] - $products->fields['total_cost'];
                                                            ?>

                                                            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href = '<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products->fields['products_id'] . '&page='); ?>'">
                                                                <td class="dataTableContent" align="right"><?php echo $products->fields['products_id']; ?>&nbsp;&nbsp;</td>
                                                                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products->fields['products_id'] . '&page=') . '">' . $products->fields['products_name'] . '</a>'; ?></td>
                                                                <td class="dataTableContent" align="right"><?php echo $products->fields['products_ordered']; ?>&nbsp;</td>
                                                                <td class="dataTableContent" align="right"><?php echo number_format($products->fields['total_final_price'], 2); ?>&nbsp;</td>
                                                                <td class="dataTableContent" align="right"><?php echo number_format($products->fields['total_cost'], 2); ?>&nbsp;</td>
                                                                <td class="dataTableContent" align="right"><?php echo number_format($products_profit, 2); ?>&nbsp;</td>
                                                                <td class="dataTableContent" align="right"><?php echo number_format($products->fields['ot_shipping'], 2); ?>&nbsp;</td>
                                                                
                                                            </tr>
                                                            <?php
                                                            $product_qty_purchase = $product_qty_purchase + (int) $products->fields['products_ordered'];
                                                            $total_products_purchased = $total_products_purchased + $products->fields['total_final_price'];
                                                            $total_cost = $total_cost + $products->fields['total_cost'];
                                                            $total_profit = $total_profit + $products_profit;
                                                            $total_shipping = $total_shipping + $products->fields['ot_shipping'];
                                                            $products->MoveNext();
                                                        }
                                                        ?>
                                                        <tr class="dataTableHeadingRow">
                                                            <td class="dataTableHeadingContent">Total</td>
                                                            <td class="dataTableHeadingContent"></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo number_format($product_qty_purchase); ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo number_format($total_products_purchased, 2); ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo number_format($total_cost, 2); ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo number_format($total_profit, 2); ?></td>
                                                            <td class="dataTableHeadingContent" align="right"><?php echo number_format($total_shipping, 2); ?>&nbsp;</td>

                                                        </tr>

                                                    </table></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                            <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, $max_page_results, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                                                            <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, $max_page_results, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table></td>
                                </tr>
                            </table></td>
                        <!-- body_text_eof //-->
                    </tr>
                </table>
            </div>

        </div>

        <!-- body_eof //-->

        <!-- footer //-->
        <div class="footer-area">
            <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        </div>
        <!-- footer_eof //-->
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
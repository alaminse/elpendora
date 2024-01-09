<?php include('../assets/header.php'); ?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">Delivery Report</h4>
        </div>
    </div>
    
    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE PRODUCT ==========-->
            <div class="mng_category">
                <div class="ep_flex">
                    <h5 class="box_title">Manage Delivery</h5>
                    <div class="btn_grp">
                        <a href="deliver-report.php?invoice" class="button btn_hover">Delivered Invoice</a>
                        <a href="deliver-report.php?all" class="button btn_hover">ALL Report</a>
                    </div>
                </div>
                
                <?php if (isset($_GET['invoice'])) {
                    // select delivery date as initial cashout date
                    $select_delivery = "SELECT DISTINCT DATE(cashout_date) as delivery_date, COUNT(id) as total_delivery FROM transaction WHERE type = 1 AND status = 1 GROUP BY DATE(cashout_date) ORDER BY DATE(cashout_date) DESC LIMIT 1";
                    $sql_delivery = mysqli_query($db, $select_delivery);
                    $num_delivery = mysqli_num_rows($sql_delivery);
                    if ($num_delivery > 0) {
                        while ($row_delivery = mysqli_fetch_assoc($sql_delivery)) {
                            $delivery_date = $row_delivery['delivery_date'];
                            $total_delivery = $row_delivery['total_delivery'];

                            // select orders
                            $select_orders = "SELECT * FROM transaction WHERE method != 'Cash' AND DATE(cashout_date) = '$delivery_date'";
                            $sql_orders = mysqli_query($db, $select_orders);
                            $num_orders = mysqli_num_rows($sql_orders);
                            if ($num_orders > 0) {
                                ?>
                                <div class="delivered_invoice_container ep_grid">
                                    <?php while ($row_orders = mysqli_fetch_assoc($sql_orders)) {
                                        $order_id = $row_orders['reference'];
                                        
                                        // fetch order
                                        $select_order    = "SELECT * FROM orders WHERE id = '$order_id'";
                                        $sql_order       = mysqli_query($db, $select_order);
                                        $row_order       = mysqli_fetch_assoc($sql_order);
                                    
                                        // fetch variable
                                        $name       = $row_order['name'];
                                        $phone      = $row_order['phone'];
                                        $address    = $row_order['address'];
                                        $pay_amount = $row_order['payment_amount'];
                                        $status     = $row_order['order_status'];
                                        $method     = $row_order['payment_method'];
                                        $date       = $row_order['order_date'];
                                        ?>
                                        <div class="delivered_invoice">
                                            <div class="ep_flex mt_75 ep_center">
                                                <strong><?php echo $status; ?> || ORDER NO - #<?php echo $order_id; ?></strong>
                                            </div>
                                            
                                            <div class="ep_flex mt_75 align_start text_medium">
                                                <div>
                                                    <div class="mb_75">
                                                        <p class="mb_5"><b>DATE</b></p>
                                                        <?php $date = date('M d, Y', strtotime($date)); echo $date;?>
                                                    </div>
                                            
                                                    <div class="mb_75">
                                                        <p class="mb_5"><b>INVOICE NO</b></p>
                                                        #<?php echo $order_id; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="text_right">
                                                    <div class="mb_75 shipping_address">
                                                        <p class="mb_5"><b>Shipping To</b></p>
                                                        <p class="mb_5"><?php echo $name; ?></p>
                                                        <p class="mb_5"><?php echo $address; ?></p>
                                                        <p class="mb_5">Phone: <?php echo $phone; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <table class="item_table">
                                                    <thead>
                                                        <tr>
                                                            <th>SI</th>
                                                            <th>Product Name</th>
                                                            <th>Qty</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php // fetch item
                                                        $select_item    = "SELECT * FROM order_details WHERE order_no = '$order_id'";
                                                        $sql_item       = mysqli_query($db, $select_item);
                                                        $si = 0;
                                                        $total = 0;
                                                        $total_qty = 0;
                                                        while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                            // fetch item variable
                                                            $product    = $row_item['product'];
                                                            $qty        = $row_item['qty'];
                                                            $price      = $row_item['price'];
                                                            $total      = $total + $price;
                                                            $total_qty  = $total_qty + $qty;
                                                            $si++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $si; ?></td>
                                                                <td><?php echo $product; ?></td>
                                                                <td><?php echo $qty; ?></td>
                                                                <td><?php echo $price; ?>/- BDT</td>
                                                            </tr>
                                                            <?php 
                                                        }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php 
                                    }?>
                                </div>
                                <?php 
                            }
                        }
                    }
                } else {
                    // select delivery date as initial cashout date
                    if (isset($_GET['all'])) {
                        $select_delivery = "SELECT DISTINCT DATE(cashout_date) as delivery_date, COUNT(id) as total_delivery FROM transaction WHERE type = 1 AND status = 1 GROUP BY DATE(cashout_date) ORDER BY DATE(cashout_date) DESC";
                    } else {
                        $select_delivery = "SELECT DISTINCT DATE(cashout_date) as delivery_date, COUNT(id) as total_delivery FROM transaction WHERE type = 1 AND status = 1 GROUP BY DATE(cashout_date) ORDER BY DATE(cashout_date) DESC LIMIT 1";
                    }
                    $sql_delivery = mysqli_query($db, $select_delivery);
                    $num_delivery = mysqli_num_rows($sql_delivery);
                    if ($num_delivery > 0) {
                        while ($row_delivery = mysqli_fetch_assoc($sql_delivery)) {
                            $delivery_date = $row_delivery['delivery_date'];
                            $total_delivery = $row_delivery['total_delivery'];
                            ?>
                            <h6 class="mb_75">Delivery Date || <?= $delivery_date ?></h6>
                            <table class="text-right">
                                <thead>
                                    <tr>
                                        <th>Total Delivery || <?= $total_delivery ?></th>
                                    </tr>
                                    
                                    <?php $sundarban = 0;
                                    $steadfast = 0;
                                    $redx = 0;
                                    
                                    // select orders
                                    $select_orders = "SELECT * FROM transaction WHERE method != 'Cash' AND DATE(cashout_date) = '$delivery_date'";
                                    $sql_orders = mysqli_query($db, $select_orders);
                                    $num_orders = mysqli_num_rows($sql_orders);
                                    if ($num_orders > 0) {
                                        while ($row_orders = mysqli_fetch_assoc($sql_orders)) {
                                            $order_id = $row_orders['reference'];
                                            
                                            // select Delivered to Sundarban
                                            $select_sundarban = "SELECT * FROM orders WHERE id = '$order_id' AND order_status = 'Delivered to Sundarban'";
                                            $sql_sundarban = mysqli_query($db, $select_sundarban);
                                            $num_sundarban = mysqli_num_rows($sql_sundarban);
                                            if ($num_sundarban > 0) {
                                                $sundarban++;
                                            }
                                            
                                            // select Delivered to SteadFast
                                            $select_steadfast = "SELECT * FROM orders WHERE id = '$order_id' AND order_status = 'Delivered to SteadFast'";
                                            $sql_steadfast = mysqli_query($db, $select_steadfast);
                                            $num_steadfast = mysqli_num_rows($sql_steadfast);
                                            if ($num_steadfast > 0) {
                                                $steadfast++;
                                            }
                                            
                                            // select Delivered to RedX
                                            $select_redx = "SELECT * FROM orders WHERE id = '$order_id' AND order_status = 'Delivered to RedX'";
                                            $sql_redx = mysqli_query($db, $select_redx);
                                            $num_redx = mysqli_num_rows($sql_redx);
                                            if ($num_redx > 0) {
                                                $redx++;
                                            }
                                            
                                            // select orders in cash
                                            $select_cash = "SELECT * FROM transaction WHERE method = 'Cash' AND type = 1 AND DATE(issued_date) = '$delivery_date'";
                                            $sql_cash = mysqli_query($db, $select_cash);
                                            $num_cash = mysqli_num_rows($sql_cash);
                                        }
                                    }?>
                                    <tr>
                                        <th>Delivered to Sundarban || <?= $sundarban ?> piece</th>
                                    </tr>
                                    
                                    <tr>
                                        <th>Delivered to SteadFast || <?= $steadfast ?> piece</th>
                                    </tr>
                                    
                                    <tr>
                                        <th>Delivered to RedX || <?= $redx ?> piece</th>
                                    </tr>
                                    
                                    <tr>
                                        <th>Cash || <?= $num_cash ?> piece</th>
                                    </tr>
                                </thead>
                            </table>
                            
                            <table class="ep_table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Product Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php // select product
                                    $select_product = "SELECT * FROM products WHERE status = 1 ORDER BY id DESC";
                                    $sql_product = mysqli_query($db, $select_product);
                                    $num_product = mysqli_num_rows($sql_product);
                                    if ($num_product > 0) {
                                        $si = 0;
                                        while ($row_product = mysqli_fetch_assoc($sql_product)) {
                                            $product_id = $row_product['id'];
                                            $product_name = $row_product['name'];
                                            $si++;
                                            
                                            $delivery_count = 0;
                                            // select orders
                                            $select_orders = "SELECT * FROM transaction WHERE method != 'Cash' AND DATE(cashout_date) = '$delivery_date'";
                                            $sql_orders = mysqli_query($db, $select_orders);
                                            $num_orders = mysqli_num_rows($sql_orders);
                                            if ($num_orders > 0) {
                                                while ($row_orders = mysqli_fetch_assoc($sql_orders)) {
                                                    $order_id = $row_orders['reference'];
                                                    
                                                    // fetch item
                                                    $select_item    = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = '$product_name'";
                                                    $sql_item       = mysqli_query($db, $select_item);
                                                    while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                        // fetch item variable
                                                        $qty = $row_item['qty'];
                                                        
                                                        $delivery_count += $qty;
                                                    }
                                                }
                                            }
                                            
                                            // select orders in cash
                                            $select_orders = "SELECT * FROM transaction WHERE method = 'Cash' AND type = 1 AND DATE(issued_date) = '$delivery_date'";
                                            $sql_orders = mysqli_query($db, $select_orders);
                                            $num_orders = mysqli_num_rows($sql_orders);
                                            if ($num_orders > 0) {
                                                while ($row_orders = mysqli_fetch_assoc($sql_orders)) {
                                                    $order_id = $row_orders['reference'];
                                                    
                                                    // fetch item
                                                    $select_item    = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = '$product_name'";
                                                    $sql_item       = mysqli_query($db, $select_item);
                                                    while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                        // fetch item variable
                                                        $qty = $row_item['qty'];
                                                        
                                                        $delivery_count += $qty;
                                                    }
                                                }
                                            }?>
                                            <tr>
                                                <td colspan="2"><?= $product_name ?></td>
                                                <td><?= $delivery_count ?> piece</td>
                                            </tr>
                                            <?php 
                                        }
                                    }?>
                                </tbody>
                            </table>
                            <?php 
                        }
                    }
                }?>
            </div>
        </div>
    </div>
    <div class="ep_section">
        <div class="ep_container">
    <table class="ep_table" id="orders">
        <thead>
            <tr>
                <th>Order No</th>
                <th>Name</th>
                <th>Product Name</th>
                <th>Phone</th>
                <th>Shipping To</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>

        <?php if (isset($_GET['invoice'])) {
                    // select delivery date as initial cashout date
                    $select_delivery = "SELECT DISTINCT DATE(cashout_date) as delivery_date, COUNT(id) as total_delivery FROM transaction WHERE type = 1 AND status = 1 GROUP BY DATE(cashout_date) ORDER BY DATE(cashout_date) DESC LIMIT 1";
                    $sql_delivery = mysqli_query($db, $select_delivery);
                    $num_delivery = mysqli_num_rows($sql_delivery);
                    if ($num_delivery > 0) {
                        while ($row_delivery = mysqli_fetch_assoc($sql_delivery)) {
                            $delivery_date = $row_delivery['delivery_date'];
                            $total_delivery = $row_delivery['total_delivery'];

                            // select orders
                            $select_orders = "SELECT * FROM transaction WHERE method != 'Cash' AND DATE(cashout_date) = '$delivery_date'";
                            $sql_orders = mysqli_query($db, $select_orders);
                            $num_orders = mysqli_num_rows($sql_orders);
                            if ($num_orders > 0) {
                                ?>
                                    <?php while ($row_orders = mysqli_fetch_assoc($sql_orders)) {
                                        $order_id = $row_orders['reference'];
                                        
                                        // fetch order
                                        $select_order    = "SELECT * FROM orders WHERE id = '$order_id'";
                                        $sql_order       = mysqli_query($db, $select_order);
                                        $row_order       = mysqli_fetch_assoc($sql_order);
                                    
                                        // fetch variable
                                        $name       = $row_order['name'];
                                        $phone      = $row_order['phone'];
                                        $address    = $row_order['address'];
                                        $pay_amount = $row_order['payment_amount'];
                                        $status     = $row_order['order_status'];
                                        $method     = $row_order['payment_method'];
                                        $date       = $row_order['order_date'];
                                        ?>
                                        <tr>
                                            <td><?php echo $order_id; ?></td>
                                            <td><?php echo $name; ?></td>
                                            <td>
                                            <?php // fetch item
                                            $select_item    = "SELECT * FROM order_details WHERE order_no = '$order_id'";
                                            $sql_item       = mysqli_query($db, $select_item);
                                            $si = 0;
                                            $total = 0;
                                            $total_qty = 0;
                                            while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                // fetch item variable
                                                $product    = $row_item['product'];
                                                $qty        = $row_item['qty'];
                                                $price      = $row_item['price'];
                                                $total      = $total + $price;
                                                $total_qty  = $total_qty + $qty;
                                                $si++;
                                                ?>
                                                <?php echo $product; ?>
                                                <?php 
                                            }?>
                                            </td>
                                            <td><?php echo $phone; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td><?php echo $address; ?></td>
                                        </tr>
                                        <?php 
                                    }?>
                                <?php 
                            }
                        }
                    }
                }
                ?>
        </tbody>
    </table>
    </div>
    </div>
</main>


<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>


<script>
/*========== LIVE PROFILE DATA QUERY =============*/
var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};
$(document).ready( function () {
    $('#orders').DataTable( {
        dom: 'Bfrtip',
        order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
<?php include('../assets/footer.php'); ?>
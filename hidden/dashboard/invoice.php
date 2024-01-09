<?php include('../db/db.php');
session_start();

// set local time zone
date_default_timezone_set('Asia/Dhaka'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Poppins:wght@700&display=swap" rel="stylesheet">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!--=========== INVOICE CSS ===========-->
    <link rel="stylesheet" href="../css/invoice.css">

    <title>Elpandora</title>
</head>
<body>

<?php if (isset($_GET['id'])) {
    $invoice = $_GET['id'];

    // fetch details
    $select_data    = "SELECT * FROM orders WHERE id = '$invoice'";
    $sql_data       = mysqli_query($db, $select_data);
    $row_data       = mysqli_fetch_assoc($sql_data);

    // fetch variable
    $name       = $row_data['name'];
    $phone      = $row_data['phone'];
    $address    = $row_data['address'];
    $pay_amount = $row_data['payment_amount'];
    $status     = $row_data['order_status'];
    $method     = $row_data['payment_method'];
    $date       = $row_data['order_date'];
    $id         = $row_data['id'];
}?>
    <div class="main-inv">
        <div class="w_100 header ep_flex">
            <div>
                <div class="logo ep_flex ep_start">
                    <img src="../images/fav_icon.png" alt="">
                    <h4>Elpandora</h4>
                </div>
                <small class="">14/A, 31/A, Concord Centre Point, <br>Tejgaon, Dhaka-1215</small>
                <small class="">017 1659 8030</small>
            </div>

            <div class="text_right">
                <div class="mb_75 shipping_address">
                    <h5 class="">Shipping To</h5>
                    <p class=""><?php echo $name; ?></p>
                    <p class=""><?php echo $address; ?></p>
                    <p class=""><b>Phone: <?php echo $phone; ?></b></p>
                </div>
            </div>
        </div>

        <div class="ep_flex align_start">
            <div>
                <div class="">
                    <small class=""><b style="margin-right: 10px;">DATE</b><?php $date = date('M d, Y', strtotime($date)); echo $date;?></small>
                </div>
            </div>
            <div class="text_right">
                <small class=""><b style="margin-right: 10px;">INVOICE NO</b> #<?php echo $id; ?></small> </br>
                <small> <strong>Status:</strong> <?php echo $status; ?></small>
            </div>
        </div>

        <div>
            <table class="item_table">
                <thead>
                    <tr>
                        <th> <small>SI</small></th>
                        <th><small>Product Name</small></th>
                        <th><small>Qty</small></th>
                        <th><small>Amount</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php // fetch item
                    $select_item    = "SELECT * FROM order_details WHERE order_no = '$invoice'";
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
                            <td><small><?php echo $si; ?></small></td>
                            <td><small><?php echo $product; ?></small></td>
                            <td><small><?php echo $qty; ?></small></td>
                            <td><small><?php echo $price; ?>/- BDT  </small></td>
                        </tr>
                        <?php 
                    }?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="footer_first">
                <div>
                    <small><b>PAYMENT METHOD</b></small>
                    <small><?php echo $method; ?></small>
                </div>
            
                <div>
                    <small><b>SHIPPING COST</b></small>
                    <small>
                        <?php if ($method == 'Cash') {
                            echo '00/- BDT';
                        } else {
                            $array = explode(',', $address);
                            $dist = end($array);
                            if ($dist == ' Dhaka' || $dist == 'Dhaka') {
                                $delivery_charge = 70;
                                echo $delivery_charge;
                            } else {
                                $delivery_charge = 60;
                                echo $delivery_charge;
                            }?>/- BDT<?php 
                        }?>
                    </small>
                </div>
            </div>

            <div class="footer_last">
                <div>
                    <small><b>TOTAL AMOUNT</b></small>
                    <small><?php echo $pay_amount; ?>/- BDT</small>
                </div>
            </div>
        </div>
    </div>
<!--=========== JQUERY ===========-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
    window.print();
</script>
</body>
</html>
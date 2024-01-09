<?php include('hidden/db/db.php');
session_start();

// set local time zone
date_default_timezone_set('Asia/Dhaka');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,600&display=swap" rel="stylesheet">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="hidden/images/fav_icon.png">
    
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!--=========== BOX ICON ===========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="css/style.css">

    <title>Elpandora - Checkout</title>
</head>
<body>
    
<header>
    <nav class="ep_container">
        <a href="https://elpandorapub.com/">
            <img src="hidden/images/logo.png" alt="" class="lg_logo">
            <img src="hidden/images/fav_icon.png" alt="" class="sm_logo">
        </a>

        <ul>
            <a href="cart.php">
                <li>
                    <i class='bx bxs-shopping-bag'></i> আমার সংগ্রহ
                </li>
            </a>
        </ul>
    </nav>
</header>

<div class="ep_container">
    <h4 class="page_title">অর্ডার করুন</h4>
</div>

<?php $alert = '';
if (isset($_POST['add_order'])) {
    $customer_name          = mysqli_escape_string($db, $_POST['customer_name']);
    $customer_phone         = mysqli_escape_string($db, $_POST['customer_phone']);
    $customer_email          = mysqli_escape_string($db, $_POST['customer_email']);
    $customer_address       = mysqli_escape_string($db, $_POST['customer_address']);
    $pay_no                 = mysqli_escape_string($db, $_POST['pay_no']);
    $trx_id                 = mysqli_escape_string($db, $_POST['trx_id']);
    $customer_dist          = $_POST['customer_dist'];
    $pay_method             = $_POST['pay_method'];
    // $order_status           = $_POST['order_status'];

    $grand_total = $_SESSION['grand_total'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address) || empty($customer_dist) || empty($pay_method)) {
        $alert = "<div class='ep_alert alert_warning'>সবগুলো ঘর পূরণ করুন.....</div>";
    } else {
        if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $alert = "<div class='ep_alert alert_warning'>সঠিক ইমেইল দিন.....</div>";
        } else {
            if (!preg_match("/^([0-9]{11})$/", $customer_phone)) {
                $alert = "<div class='ep_alert alert_warning'>সঠিক ফোন নাম্বার দিন.....</div>";
            } else {
                // create full address
                $customer_address       = $customer_address.', '.$customer_dist;

                if ($pay_method == 'Bkash' || $pay_method == 'Nagad') {
                    if (empty($pay_no) || empty($trx_id)) {
                        $alert = "<div class='ep_alert alert_warning'>যে নাম্বার থেকে টাকা পাঠিয়েছেন সেই নাম্বার এবং ট্রান্সেকশন আইডি দিন.....</div>";
                    } else {
                        $payment_status = 0;
                        $order_status = 'On Hold';

                        // fetch duplicate trx id
                        $fetch_trx  = "SELECT * FROM orders WHERE trx_id = '$trx_id'";
                        $sql_trx    = mysqli_query($db, $fetch_trx);
                        $num_trx    = mysqli_num_rows($sql_trx);
                        if ($num_trx > 0) {
                            $alert = "<div class='ep_alert alert_danger'>ট্রান্সেকশন আইডিটি আগেও ব্যবহার হয়েছে তাই এটি গ্রহনযোগ্য নয়.....</div>";
                        } else {
                            // insert order
                            $insert_order = "INSERT INTO orders (name, phone, email, address, order_status, payment_status, payment_method, payment_number, trx_id, payment_amount, order_date) VALUES ('$customer_name', '$customer_phone', '$customer_email', '$customer_address', '$order_status', '$payment_status', '$pay_method', '$pay_no', '$trx_id', '$grand_total', '$created_date')";

                            if (mysqli_query($db, $insert_order)) {
                                // get order id
                                $order_id = mysqli_insert_id($db);
                                $_SESSION['order_id'] = mysqli_insert_id($db);

                                // insert transaction
                                $insert_transaction = "INSERT INTO transaction (method, pay_no, trxid, total, type, status, reference, issued_date) VALUES ('$pay_method', '$pay_no', '$trx_id', '$grand_total', 1, '$payment_status', '$order_id', '$created_date')";
                                mysqli_query($db, $insert_transaction);

                                // insert ordered items
                                $insert_order_items = "INSERT INTO order_details (order_no, product, qty, price) VALUES (?, ?, ?, ?)";

                                $prepare_items = mysqli_prepare($db, $insert_order_items);

                                if ($prepare_items) {
                                    mysqli_stmt_bind_param($prepare_items, 'isii', $order_id, $product, $qty, $price);
                                    foreach($_SESSION['cart'] as $key => $values) {
                                        $product = $values['name'];
                                        $qty = $values['qty'];
                                        $price = $values['price'];
                                        mysqli_stmt_execute($prepare_items);
                                    }

                                    $msg = "Your order has been placed successfully. Your Order No. is ".$order_id.". Your can track your parcel in our website by this Order Number";
                                    
                                    if ($product == 'Cycle-1,2 (HSC Exam Enhancer)' || $product == 'Cycle-1 (HSC Exam Enhancer)') {
                                        $msg = "Thank you for preordering. Your Order No. is ".$order_id.". We will send product to courier after 10 December.";
                                    }

                                    // send sms to customer
                                    $to = "$customer_phone";
                                    $token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
                                    $message = "$msg";

                                    $url = "http://api.greenweb.com.bd/api.php?json";


                                    $data= array(
                                    'to'=>"$to",
                                    'message'=>"$message",
                                    'token'=>"$token"
                                    ); 
                                    $ch = curl_init(); 
                                    curl_setopt($ch, CURLOPT_URL,$url);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                    curl_setopt($ch, CURLOPT_ENCODING, '');
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $smsresult = curl_exec($ch);

                                    // unset cart
                                    unset($_SESSION['cart']);
                                    unset($_SESSION['districtValue']);
                                    unset($_SESSION['payMethodValue']);
                                    unset($_SESSION['total_qty']);
                                    unset($_SESSION['subtotal']);
                                    unset($_SESSION['delivery_charge']);
                                    unset($_SESSION['delivery_text']);
                                    unset($_SESSION['subintotal']);
                                    unset($_SESSION['transaction_charge']);
                                    unset($_SESSION['transaction_text']);
                                    unset($_SESSION['grand_total']);
                                    unset($_SESSION['grand_total_text']);
                                    header('Location: order-success.php');
                                } else {
                                    header('Location: http://elpandora.com/');
                                }
                            }
                        }
                    }
                } else {
                    if ($pay_method == 'Cash') {
                        $order_status = 'Complete';
                        $payment_status = 1;
                    } elseif ($pay_method == 'Cash on Delivery') {
                        $order_status = 'On Hold';
                        $payment_status = 0;
                    }

                    $insert_order = "INSERT INTO orders (name, phone, email, address, order_status, payment_status, payment_method, payment_amount, order_date) VALUES ('$customer_name', '$customer_phone', '$customer_email', '$customer_address', '$order_status', '$payment_status', '$pay_method', '$grand_total', '$created_date')";

                    if (mysqli_query($db, $insert_order)) {
                        // get order id
                        $order_id = mysqli_insert_id($db);
                        $_SESSION['order_id'] = mysqli_insert_id($db);

                        // insert transaction
                        $insert_transaction = "INSERT INTO transaction (method, total, type, status, reference, issued_date) VALUES ('$pay_method', '$grand_total', 1, '$payment_status', '$order_id', '$created_date')";
                        mysqli_query($db, $insert_transaction);

                        // insert ordered items
                        $insert_order_items = "INSERT INTO order_details (order_no, product, qty, price) VALUES (?, ?, ?, ?)";

                        $prepare_items = mysqli_prepare($db, $insert_order_items);

                        if ($prepare_items) {
                            mysqli_stmt_bind_param($prepare_items, 'isii', $order_id, $product, $qty, $price);
                            foreach($_SESSION['cart'] as $key => $values) {
                                $product = $values['name'];
                                $qty = $values['qty'];
                                $price = $values['price'];
                                mysqli_stmt_execute($prepare_items);
                            }

                            $msg = "Your order has been placed successfully. Your Order No. is ".$order_id.". Your can track your parcel in our website by this Order Number";
                            
                            if ($product == 'Cycle-1,2 (HSC Exam Enhancer)' || $product == 'Cycle-1 (HSC Exam Enhancer)') {
                                $msg = "Thank you for preordering. Your Order No. is ".$order_id.". We will send product to courier after 10 December.";
                            }

                            // send sms to customer
                            $to = "$customer_phone";
                            $token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
                            $message = "$msg";

                            $url = "http://api.greenweb.com.bd/api.php?json";


                            $data= array(
                            'to'=>"$to",
                            'message'=>"$message",
                            'token'=>"$token"
                            ); 
                            $ch = curl_init(); 
                            curl_setopt($ch, CURLOPT_URL,$url);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_ENCODING, '');
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $smsresult = curl_exec($ch);

                            // unset cart
                            unset($_SESSION['cart']);
                            unset($_SESSION['districtValue']);
                            unset($_SESSION['payMethodValue']);
                            unset($_SESSION['total_qty']);
                            unset($_SESSION['subtotal']);
                            unset($_SESSION['delivery_charge']);
                            unset($_SESSION['delivery_text']);
                            unset($_SESSION['subintotal']);
                            unset($_SESSION['transaction_charge']);
                            unset($_SESSION['transaction_text']);
                            unset($_SESSION['grand_total']);
                            unset($_SESSION['grand_total_text']);
                            header('Location: order-success.php');
                        } else {
                            header('Location: http://elpandora.com/');
                        }
                    }
                }
            }
        }
    }
}?>

<div class="ep_container">
    <?php echo $alert; ?>

    <div class="">
        <form action="" method="post" class="double_col_form product_form">
            <div class="input_container">
                <div>
                    <label for="customer-name">নাম*</label>
                    <input type="text" id="customer-name" name="customer_name" placeholder="Customer Name">
                </div>

                <div>
                    <label for="customer-phone">ফোন নাম্বার*</label>
                    <input type="text" id="customer-phone" name="customer_phone" placeholder="Customer Phone">
                </div>

                <div>
                    <label for="customer-email">ইমেইল*</label>
                    <input type="text" id="customer-email" name="customer_email" placeholder="Customer Email">
                </div>

                <div class="span_3">
                    <label for="address">ঠিকানা*</label>
                    <textarea id="address" name="customer_address" placeholder="Customer Address" rows="2"></textarea>
                </div>

                <div>
                    <label for="district">জেলা*</label>
                    <select id="district" name="customer_dist" class="for_total cashon_dev">
                        <option value="">Choose District</option>
                        <?php $select_district = "SELECT * FROM district ORDER BY name ASC";
                        $sql_district = mysqli_query($db, $select_district);
                        $num_district = mysqli_num_rows($sql_district);
                        if ($num_district > 0) {
                            while ($row_district= mysqli_fetch_assoc($sql_district)) {
                                $district_id     = $row_district['id'];
                                $district_name   = $row_district['name'];
                                ?>
                                <option value="<?php if ($district_name == 'Dhaka City') { echo 'Dhaka'; } else { echo $district_name; } ?>"><?php echo $district_name; ?></option>
                                <?php 
                            }
                        }?>
                    </select>
                </div>

                <div>
                    <label for="pay-method">পেমেন্টের পদ্ধতি*</label>
                    <select id="pay-method" name="pay_method" class="for_total pay_method">
                        <option value="">Choose Method</option>
                        <option value="Bkash">Bkash</option>
                        <option value="Nagad">Nagad</option>
                    </select>
                </div>
                
                <div class="input_container" id="input-container"></div>

                <!--<div>-->
                <!--    <label>যেই নাম্বারে টাকা পাঠাবেনঃ</label>-->
                <!--    <p>বিকাশঃ 01716598030 (পেমেন্ট)</p>-->
                <!--    <p>নগদঃ 01320793710 (সেন্ড মানি)</p>-->
                <!--</div>-->

                <!--<div>-->
                <!--    <label for="pay-no">পেমেন্টের নাম্বার</label>-->
                <!--    <input type="text" id="pay-no" name="pay_no" placeholder="Payment Number">-->
                <!--</div>-->

                <!--<div>-->
                <!--    <label for="trx-id">ট্রান্সেকশন আইডি</label>-->
                <!--    <input type="text" id="trx-id" name="trx_id" placeholder="Transaction ID">-->
                <!--</div>-->
            </div>

            <div class="summary">
                <div class="cart_grid summary_box">
                    <h4 class="box_title">Order Summery</h4>

                    <p class="ep_flex"><span class="text_semi">Subtotal: </span><span><?php $total_price = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $key => $value) {
                            $total_price    = $total_price + $value['price'];
                        }
                        echo $total_price.'.00/- BDT'; ?>
                        <?php 
                    }?></span></p>

                    <div id="charge">
                        <?php if (isset($_SESSION['delivery_text'])) {
                            echo $_SESSION['delivery_text'];
                        } else {
                            echo '<p class="ep_flex"><span class="text_semi">Delivery Charge: </span><span>0.00/- BDT</span></p>';
                        }
                        if (isset($_SESSION['transaction_text'])) {
                            echo $_SESSION['transaction_text'];
                        } else {
                            echo '<p class="ep_flex"><span class="text_semi">Transaction Charge: </span><span>0.00/- BDT</span></p>';
                        }?>

                        <?php if (isset($_SESSION['grand_total_text'])) {
                            echo $_SESSION['grand_total_text'];
                        } else {
                            echo '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$total_price.'.00/- BDT</span></p>';
                        }?>
                    </div>
                </div>

                <button type="submit" name="add_order" class="w_100">অর্ডার করুন</button>
            </div>
        </form>
    </div>
</div>

<!--=========== FOOTER ===========-->
<footer>
    <div class="footer ep_container ep_grid">
        <div>
            <a href="http://elpandora.com/">
                <img src="hidden/images/logo.png" alt="" class="footer_logo">
            </a>
            <div class="tagline">Books make us Birds.</div>

            <div class="ep_flex ep_start footer_list mb_75">
                <i class='bx bxs-map'></i>
                <p>14/A, 31/A, Concord Centre Point, Tejgaon, Dhaka-1215</p>
            </div>
            <div class="ep_flex ep_start footer_list">
                <i class='bx bxs-phone' ></i>
                <p>017 1659 8030</p>
            </div>
        </div>
    </div>

    <div class="copyright ep_container">
        ELPANDORA &copy; COPYRIGHT 2023 DEVELOPED BY MEHEDI HASAN.
    </div>
</footer>

<script>
$(document).ready(function() {
    // Update Delivery Charge On the basis of District
    $('.for_total').on('change', function() {
        var districtValue = $('#district').val();
        var payMethodValue = $('#pay-method').val();
        $.ajax({
            url: 'charge.php',
            type: 'POST',
            data: {'districtValue': districtValue, 'payMethodValue': payMethodValue},
            success: function(data) {
                $('#charge').html(data);
            }
        });
    });
});

$(document).ready(function() {
    // Update Delivery Charge On the basis of District
    $('.cashon_dev').on('change', function() {
        var districtValue = $('#district').val();
        $.ajax({
            url: 'pay-method.php',
            type: 'POST',
            data: {'districtValue': districtValue,},
            success: function(data) {
                $('#pay-method').html(data);
            }
        });
    });
});

$(document).ready(function() {
    // Update Delivery Charge On the basis of District
    $('.pay_method').on('change', function() {
        var payMethodValue = $('#pay-method').val();
        $.ajax({
            url: 'pay-info.php',
            type: 'POST',
            data: {'payMethodValue': payMethodValue,},
            success: function(data) {
                $('#input-container').html(data);
            }
        });
    });
});
</script>

</body>
</html>
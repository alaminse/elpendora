<?php include('../assets/header.php'); ?>

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

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address) || empty($pay_method)) {
        $alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
    } else {
        if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $alert = "<div class='alert alert-warning'>Incorrect Email.....</div>";
        } else {
            if (!preg_match("/^([0-9]{11})$/", $customer_phone)) {
                $alert = "<div class='alert alert-warning'>Incorrect Phone Number.....</div>";
            } else {
                // create full address
                $customer_address       = $customer_address.', '.$customer_dist;

                if ($pay_method == 'Bkash' || $pay_method == 'Nagad') {
                    if (empty($pay_no) || empty($trx_id)) {
                        $alert = "<div class='alert alert-warning'>Give Payment Number And Transaction ID.....</div>";
                    } else {
                        $payment_status = 0;
                        $order_status = 'On Hold';

                        // fetch duplicate trx id
                        $fetch_trx  = "SELECT * FROM orders WHERE trx_id = '$trx_id'";
                        $sql_trx    = mysqli_query($db, $fetch_trx);
                        $num_trx    = mysqli_num_rows($sql_trx);
                        if ($num_trx > 0) {
                            $alert = "<div class='alert alert-danger'>This transactyion id has already taken.....</div>";
                        } else {
                            // insert order
                            $insert_order = "INSERT INTO orders (name, phone, email, address, order_status, payment_status, payment_method, payment_number, trx_id, payment_amount, order_date) VALUES ('$customer_name', '$customer_phone', '$customer_email', '$customer_address', '$order_status', '$payment_status', '$pay_method', '$pay_no', '$trx_id', '$grand_total', '$created_date')";

                            if (mysqli_query($db, $insert_order)) {
                                // get order id
                                $order_id = mysqli_insert_id($db);

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
                                    
                                    if ($product = 'Cycle-1,2 (HSC Exam Enhancer)' || $product = 'Cycle-1 (HSC Exam Enhancer)') {
                                        $msg = "Thank you for preordering. Your Order No. is ".$order_id.". We will send product to courier after 30 Nov.";
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
                                    header('Location: order.php');
                                } else {
                                    header('Location: order.php');
                                }
                            }
                        }
                    }
                } else {
                    if ($pay_method == 'Cash') {
                        $order_status = 'Complete';
                        $payment_status = 1;
                    } elseif ($pay_method == 'Cash on Delivery') {
                        $order_status = 'Processing';
                        $payment_status = 0;
                    }

                    $insert_order = "INSERT INTO orders (name, phone, email, address, order_status, payment_status, payment_method, payment_amount, order_date) VALUES ('$customer_name', '$customer_phone', '$customer_email', '$customer_address', '$order_status', '$payment_status', '$pay_method', '$grand_total', '$created_date')";

                    if (mysqli_query($db, $insert_order)) {
                        // get order id
                        $order_id = mysqli_insert_id($db);

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
                            
                            if ($pay_method == 'Cash') {
                                $msg = "You has bought book by cash from Elpandora";
                            } elseif ($pay_method == 'Cash on Delivery') {
                                $msg = "Your order has been placed successfully. Your Order No. is ".$order_id.". Your can track your parcel in our website by this Order Number";
                                
                                if ($product = 'Cycle-1,2 (HSC Exam Enhancer)' || $product = 'Cycle-1 (HSC Exam Enhancer)') {
                                    $msg = "Thank you for preordering. Your Order No. is ".$order_id.". We will send product to courier after 30 Nov.";
                                }
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
                            header('Location: order.php');
                        } else {
                            header('Location: order.php');
                        }
                    }
                }
            }
        }
    }
}?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">All Orders</h4>
        </div>
    </div>

    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD PRODUCT ==========-->
            <?php if (isset($_GET['add'])) {
                $cart_alert = '';
                if (isset($_POST['add_cart'])) {
                    if (isset($_SESSION['cart'])) {
                        $session_array_id = array_column($_SESSION['cart'], 'id');
                        $cart_count = count($_SESSION['cart']);

                        $_SESSION['cart'][$cart_count] = array(
                            'id' => $_POST['product_id'],
                            'name' => $_POST['product_name'],
                            'price' => $_POST['product_price'],
                            'qty' => $_POST['product_qty'],
                        );
                        $cart_alert = "<div class='alert alert-success'>Product Successfully added</div>";

                        if (in_array($_POST['product_id'], $session_array_id)) {
                            echo '<script>alert("Item Added"); window.location.href = "order.php?add";</script>';
                        }
                    } else {
                        $_SESSION['cart'][0] = array(
                            'id' => $_POST['product_id'],
                            'name' => $_POST['product_name'],
                            'price' => $_POST['product_price'],
                            'qty' => $_POST['product_qty'],
                        );

                        $cart_alert = "<div class='alert alert-success'>Product Successfully added</div>";
                    }
                }?>
                <div class="product_grid">
                    <div class="ep_flex">
                        <h4 class="box_title">All Products</h4>
                        <a href="order.php?cart" >Go to Cart</a>
                    </div>

                    <?php echo $cart_alert; ?> 

                    <div class="ep_grid product_cards product_grid">
                        <?php $select_product = "SELECT * FROM products ORDER BY id DESC";
                        $sql_product = mysqli_query($db, $select_product);
                        $num_product = mysqli_num_rows($sql_product);
                        if ($num_product > 0) {
                            while ($row_product = mysqli_fetch_assoc($sql_product)) {
                                $product_id         = $row_product['id'];
                                $product_image      = $row_product['product_img'];
                                $product_name       = $row_product['name'];
                                $product_price      = $row_product['price'];
                                $product_sale       = $row_product['offer_price'];
                                $product_stock       = $row_product['stock'];
                                ?>
                                <form method="post" action="" class="product_card">
                                    <div class="product_card_img">
                                        <img src="../products/<?php echo $product_image; ?>" alt="">
                                    </div>
                                    <div class="product_card_data text_center">
                                        <p class="product_card_title text_semi"><?php echo $product_name; ?></p>
                                        <div class="product_card_price text_semi">
                                        <?php if (empty($product_sale)) {
                                            echo '<p class="text_semi">'.$product_price.'.00/- BDT</p></td>';
                                        } else {
                                            echo '<span class="text_sm text_strike">'.$product_price.'.00/- BDT</span><br>
                                            <p class="text_semi">'.$product_sale.'.00/- BDT</p>';
                                        }?></div>
                                        <?php if ($product_stock == 1) {
                                            echo '<div class="ep_badge bg_success text_success mb_75 m_auto">In Stock</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger mb_75 m_auto">Out of Stock</div>';
                                        }?>
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                                        <input type="hidden" name="product_price" value="<?php if (empty($product_sale)) {
                                            echo $product_price;
                                        } else {
                                            echo $product_sale;
                                        }?>">
                                        <input type="hidden" name="product_qty" value="1">
                                        <?php if ($product_stock == 1) {
                                            echo '<button type="submit" name="add_cart">Add to Cart</button>';
                                        }?>
                                    </div>
                                </form>
                                <?php 
                            }
                        }?>
                    </div>
                </div>
                <?php 
            } elseif (isset($_GET['cart'])) {
                $cart_alert = '';
                if (isset($_GET['remove'])) {
                    $remove = $_GET['remove'];
                    if ($remove == 'all') {
                        unset($_SESSION['cart']);
                        $cart_alert = "<div class='alert alert-success'>Clear All Product From Cart</div>";
                    } else {
                        foreach($_SESSION['cart'] as $key => $value) {
                            if ($value['id'] == $remove) {
                                unset($_SESSION['cart'][$key]);
                            }
                        }
                        $cart_alert = "<div class='alert alert-success'>Clear Product From Cart</div>";
                    }
                }

                if (isset($_POST['update_product_qty'])) {
                    foreach($_SESSION['cart'] as $key => $value) {
                        if ($value['id'] == $_POST['update_product_id']) {
                            $_SESSION['cart'][$key]['price'] = ($_SESSION['cart'][$key]['price'] / $_SESSION['cart'][$key]['qty']) * $_POST['update_product_qty'];
                            $_SESSION['cart'][$key]['qty'] = $_POST['update_product_qty'];
                        }
                    }
                    $cart_alert = "<div class='alert alert-success'>Update Product Quantity Successfully</div>";
                }
                ?>
                <div class="ep_flex">
                    <h4 class="box_title">Cart Page</h4>
                    <a href="order.php?add" >Continue Shop</a>
                </div>

                <?php echo $cart_alert; ?>
                
                <div class="cart_grid ep_grid">
                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_price = 0;
                            if (!empty($_SESSION['cart'])) {
                                foreach($_SESSION['cart'] as $key => $value) {
                                    $total_price = $total_price + $value['price'];
                                    ?>
                                    <tr>
                                        <td><?php echo $value['name']; ?></td>
                                        <td><?php echo $value['price']; ?><input type="hidden" class="cart_price" value="<?php echo $value['price']; ?>"></td>
                                        <td>
                                            <form action="" method="post">
                                                <input type="number" name="update_product_qty" id="" value="<?php echo $value['qty']; ?>" min="1" max="5" class="cart_qty" onchange="this.form.submit()">
                                                <input type="hidden" name="update_product_id" id="" value="<?php echo $value['id']; ?>">
                                            </form>
                                        </td>
                                        <td><span class="cart_total"><?php echo $value['price']; ?></span><span>.00/- BDT</span></td>
                                        <td><a href="order.php?cart&remove=<?php echo $value['id']; ?>">Remove</a></td>
                                    </tr>
                                    <?php 
                                }
                                ?>
                                <tr>
                                    <td colspan="3"></td>
                                    <td><span id="total-price"><?php echo $total_price;?></span><span>.00/- BDT</span></td>
                                    <td><a href="order.php?cart&remove=all">Clear All</a></td>
                                </tr>
                                <?php 
                            } else {
                                echo "<tr><td colspan='5' class='text_center'>Cart is empty</td></tr>";
                            }?>
                        </tbody>
                    </table>
                    <?php if (!empty($_SESSION['cart'])) {
                        echo '<a href="order.php?checkout"><button>Checkout Now</button></a>';
                    }?>
                </div>
                <?php 
            } elseif (isset($_GET['checkout'])) {
                ?>
                <div class="add_category">
                    <h5 class="box_title">Create Order</h5>

                    <?php echo $alert; ?>

                    <div class="ep_grid grid_2_1 gap_3">
                        <form action="" method="post" class="double_col_form product_form">
                            <div>
                                <label for="customer-name">Customer Name*</label>
                                <input type="text" id="customer-name" name="customer_name" placeholder="Customer Name">
                            </div>

                            <div>
                                <label for="customer-phone">Phone*</label>
                                <input type="text" id="customer-phone" name="customer_phone" placeholder="Customer Phone">
                            </div>

                            <div>
                                <label for="customer-email">Email*</label>
                                <input type="text" id="customer-email" name="customer_email" placeholder="Customer Email">
                            </div>

                            <div class="span_3">
                                <label for="address">Address*</label>
                                <textarea id="address" name="customer_address" placeholder="Customer Address" rows="2"></textarea>
                            </div>

                            <div>
                                <label for="district">District*</label>
                                <select id="district" name="customer_dist" class="for_total cashon_dev">
                                    <option>Choose District</option>
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
                                <label for="pay-method">Payment Method*</label>
                                <select id="pay-method" name="pay_method" class="for_total">
                                    <option>Choose Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                </select>
                            </div>

                            <div>
                                <label for="pay-no">Payment No</label>
                                <input type="text" id="pay-no" name="pay_no" placeholder="Payment Number">
                            </div>

                            <div>
                                <label for="trx-id">Transaction ID</label>
                                <input type="text" id="trx-id" name="trx_id" placeholder="Transaction ID">
                            </div>

                            <!-- <div>
                                <label for="order-status">Status</label>
                                <select id="order-status" name="order_status">
                                    <option>Choose Status</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered to Sundarban">Delivered to Sundarban</option>
                                    <option value="Delivered to RedX">Delivered to RedX</option>
                                    <option value="Complete">Complete</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div> -->

                            <button type="submit" name="add_order">Create Order</button>
                        </form>

                        <div class="cart_grid summery_box">
                            <h5 class="box_title">Order Summery</h5>

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
                    </div>
                </div>
                <?php 
            } elseif (isset($_GET['edit'])) {
                $edit = $_GET['edit'];

                $edit_alert = '';
                $select_order = "SELECT * FROM orders WHERE id = '$edit'";
                $sql_order = mysqli_query($db, $select_order);
                $row_order = mysqli_fetch_assoc($sql_order);
                $order_id       = $row_order['id'];
                $name           = $row_order['name'];
                $email          = $row_order['email'];
                $phone          = $row_order['phone'];
                $address        = $row_order['address'];
                $pay_method     = $row_order['payment_method'];
                $pay_no         = $row_order['payment_number'];
                $pay_status     = $row_order['payment_status'];
                $trx_id         = $row_order['trx_id'];
                $status         = $row_order['order_status'];
                $amount         = $row_order['payment_amount'];

                if (isset($_POST['update_order'])) {
                    $update_customer_name           = mysqli_escape_string($db, $_POST['update_customer_name']);
                    $update_customer_phone          = mysqli_escape_string($db, $_POST['update_customer_phone']);
                    $update_customer_email          = mysqli_escape_string($db, $_POST['update_customer_email']);
                    $update_customer_address        = mysqli_escape_string($db, $_POST['update_customer_address']);
                    $update_pay_no                  = mysqli_escape_string($db, $_POST['update_pay_no']);
                    $update_trx_id                  = mysqli_escape_string($db, $_POST['update_trx_id']);
                    $update_pay_status              = $_POST['update_pay_status'];
                    $update_order_status            = $_POST['update_order_status'];

                    if (!filter_var($update_customer_email, FILTER_VALIDATE_EMAIL)) {
                        $edit_alert = "<div class='alert alert-warning'>Incorrect Email.....</div>";
                    } else {
                        if (!preg_match("/^([0-9]{11})$/", $update_customer_phone)) {
                            $edit_alert = "<div class='alert alert-warning'>Incorrect Phone Number.....</div>";
                        } else {
                            // update order
                            $update_order = "UPDATE orders SET name = '$update_customer_name', phone = '$update_customer_phone', email = '$update_customer_email', address = '$update_customer_address', order_status = '$update_order_status', payment_status = '$update_pay_status', payment_number = '$update_pay_no', trx_id = '$update_trx_id' WHERE id = '$edit'";

                            if ($update_order_status == 'Cancelled') {
                                $type = 3;
                            } else {
                                $type = 1;
                            }
                            
                            // update transaction
                            $update_transaction = "UPDATE transaction SET pay_no = '$update_pay_no', trxid = '$update_trx_id', type = '$type', status = '$update_pay_status' WHERE reference = '$edit'";

                            // send sms to customer if order status is cancelled or delivered
                            if ($update_order_status == 'Cancelled' || $update_order_status == 'Delivered to Sundarban' || $update_order_status == 'Delivered to RedX' || $update_order_status == 'Delivered to SteadFast') {
                                if ($update_order_status == 'Cancelled') {
                                    $msg = 'Your order has been cancelled. If you pay us money, you will get refund within 2 working days';
                                } elseif ($update_order_status == 'Delivered to Sundarban' || $update_order_status == 'Delivered to RedX' || $update_order_status == 'Delivered to SteadFast') {
                                    $msg = 'Your parcel has been '.$update_order_status.'. Please keep you phone number active to get sms or phone call from Percel Provider';
                                }

                                // send sms to customer
                                $to = "$update_customer_phone";
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
                            }

                            if (mysqli_query($db, $update_order) && mysqli_query($db, $update_transaction)) {
                                if (($update_pay_status = '1') && ($update_order_status == 'Delivered to Sundarban' || $update_order_status == 'Delivered to RedX' || $update_order_status == 'Delivered to SteadFast')) {
                                    $cashout_date = date('Y-m-d H:i:s', time());

                                    // update transaction
                                    $update_status = "UPDATE transaction SET cashout_date = '$cashout_date' WHERE reference = '$edit'";
                                    mysqli_query($db, $update_status);
                                }?>
                                <script type="text/javascript">
                                    window.location.href = 'order.php?edit=<?php echo $edit; ?>';
                                </script>
                                <?php
                            } else {
                                header('Location: 404.php');
                            }
                        }
                    }
                }?>
                <div class="add_category">
                    <h5 class="box_title">Update Order - [ Order No - <?= $order_id ?> ]</h5>

                    <?php echo $edit_alert; ?>

                    <div class="ep_grid grid_2_1 gap_3">
                        <form action="" method="post" class="double_col_form product_form">
                            <div>
                                <label for="customer-name">Customer Name*</label>
                                <input type="text" id="customer-name" name="update_customer_name" placeholder="Customer Name" value="<?php echo $name; ?>">
                            </div>

                            <div>
                                <label for="customer-phone">Phone*</label>
                                <input type="text" id="customer-phone" name="update_customer_phone" placeholder="Customer Phone" value="<?php echo $phone; ?>">
                            </div>

                            <div>
                                <label for="customer-email">Email*</label>
                                <input type="text" id="customer-email" name="update_customer_email" placeholder="Customer Email" value="<?php echo $email; ?>">
                            </div>

                            <div class="span_3">
                                <label for="address">Address*</label>
                                <textarea id="address" name="update_customer_address" placeholder="Customer Address" rows="2"><?php echo $address; ?></textarea>
                            </div>

                            <div>
                                <label for="pay-method">Payment Method*</label>
                                <select id="pay-method" name="update_pay_method" class="for_total">
                                    <option>Choose Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bkash" <?php if ($pay_method == 'Bkash') {echo 'selected';}?>>Bkash</option>
                                    <option value="Nagad" <?php if ($pay_method == 'Nagad') {echo 'selected';}?>>Nagad</option>
                                    <option value="Cash on Delivery" <?php if ($pay_method == 'Cash on Delivery') {echo 'selected';}?>>Cash on Delivery</option>
                                </select>
                            </div>

                            <div>
                                <label for="pay-no">Payment No</label>
                                <input type="text" id="pay-no" name="update_pay_no" placeholder="Payment Number" value="<?php echo $pay_no; ?>">
                            </div>

                            <div>
                                <label for="trx-id">Transaction ID</label>
                                <input type="text" id="trx-id" name="update_trx_id" placeholder="Transaction ID" value="<?php echo $trx_id; ?>">
                            </div>

                            <div>
                                <label for="order-status">Status</label>
                                <select id="order-status" name="update_order_status">
                                    <option>Choose Status</option>
                                    <option value="On Hold" <?php if ($status == 'On Hold') {echo 'selected';}?>>On Hold</option>
                                    <option value="Processing" <?php if ($status == 'Processing') {echo 'selected';}?>>Processing</option>
                                    <option value="Delivered to Sundarban" <?php if ($status == 'Delivered to Sundarban') {echo 'selected';}?>>Delivered to Sundarban</option>
                                    <option value="Delivered to SteadFast" <?php if ($status == 'Delivered to SteadFast') {echo 'selected';}?>>Delivered to SteadFast</option>
                                    <option value="Delivered to RedX" <?php if ($status == 'Delivered to RedX') {echo 'selected';}?>>Delivered to RedX</option>
                                    <option value="Complete" <?php if ($status == 'Complete') {echo 'selected';}?>>Complete</option>
                                    <option value="Cancelled" <?php if ($status == 'Cancelled') {echo 'selected';}?>>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="pay-status">Payment Status</label>
                                <select id="pay-status" name="update_pay_status">
                                    <option>Choose Status</option>
                                    <option value="0" <?php if ($pay_status == '0') {echo 'selected';}?>>Unverified</option>
                                    <option value="1" <?php if ($pay_status == '1') {echo 'selected';}?>>Verified</option>
                                </select>
                            </div>

                            <button type="submit" name="update_order">Update Order</button>
                        </form>

                        <div class="cart_grid summery_box">
                            <h5 class="box_title">Order Summery</h5>

                            <div id="charge">
                                <?php echo '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$amount.'.00/- BDT</span></p>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="add_category">
                    <table class="ep_table">
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
                            $select_item    = "SELECT * FROM order_details WHERE order_no = '$edit'";
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
                    
                    <div class="ep_flex ep_end">
                        <a href="invoice.php?id=<?php echo $order_id; ?>" target="_blank" class="button no_hover"><i class='bx bxs-printer'></i> Print Invoice</a>
                    </div>
                </div>
                <?php 
            } else {
                ?>
                <!--========== MANAGE PRODUCT ==========-->
                <div class="mng_category">
                    <div class="ep_flex">
                        <h5 class="box_title">Manage Product</h5>
                        <a href="order.php?add" class="button btn_hover">Create Order</a>
                    </div>

                    <table class="ep_table" id="orders">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Payment No</th>
                                <th>Trx ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_order = "SELECT * FROM orders ORDER BY id DESC";
                            $sql_order = mysqli_query($db, $select_order);
                            $num_order = mysqli_num_rows($sql_order);
                            if ($num_order == 0) {
                                echo "<tr><td colspan='11' class='text_center'>There are no order</td></tr>";
                            } else {
                                while ($row_order = mysqli_fetch_assoc($sql_order)) {
                                    $order_id           = $row_order['id'];
                                    $order_name         = $row_order['name'];
                                    $order_status       = $row_order['order_status'];
                                    $order_total        = $row_order['payment_amount'];
                                    $order_pay_status   = $row_order['payment_status'];
                                    $order_pay_method   = $row_order['payment_method'];
                                    $order_pay_no       = $row_order['payment_number'];
                                    $order_trxid        = $row_order['trx_id'];
                                    $order_date         = $row_order['order_date'];
                                    ?>
                                    <tr>
                                        <td><?php echo $order_id; ?></td>

                                        <td><?php echo $order_name; ?></td>

                                        <td class="text_sm"><?php $now = date('Y-m-d H:i:s');
                                        $read_time = date('Y-m-d H:i:s', strtotime($order_date));

                                        $dt1 = new DateTime($read_time);
                                        $dt2 = new DateTime($now);
                                        $time_diff = $dt1->diff($dt2)->format('%r%y years, %m months, %d days, %h hours, %i minutes, %s seconds');

                                        $time_diff_y = $dt1->diff($dt2) -> format('%r%y');
                                        $time_diff_m = $dt1->diff($dt2) -> format('%r%m');
                                        $time_diff_d = $dt1->diff($dt2) -> format('%r%d');
                                        $time_diff_h = $dt1->diff($dt2) -> format('%r%h');
                                        $time_diff_i = $dt1->diff($dt2) -> format('%r%i');
                                        $time_diff_s = $dt1->diff($dt2) -> format('%r%s');

                                        if ($time_diff_y > 0) {
                                            $order_date = date('d M Y', strtotime($order_date));
                                            echo $order_date;
                                        } else {
                                            if ($time_diff_m > 0) {
                                                $order_date = date('d M Y', strtotime($order_date));
                                                echo $order_date;
                                            } else {
                                                if ($time_diff_d > 0) {
                                                    $order_date = date('d M Y', strtotime($order_date));
                                                    echo $order_date;
                                                } else {
                                                    if ($time_diff_h > 0) {
                                                        $ago_time = $time_diff_h." hour ago";
                                                        echo $ago_time;
                                                    } else {
                                                        if ($time_diff_i > 0) {
                                                            $ago_time = $time_diff_i." min ago";
                                                            echo $ago_time;
                                                        } else {
                                                            $ago_time = "few sec ago";
                                                            echo $ago_time;
                                                        }
                                                    }
                                                }
                                            } 
                                        }?></td>

                                        <td><?php if ($order_status == 'Complete') {
                                            echo '<div class="ep_badge bg_success text_success">Complete</div>';
                                        } elseif ($order_status == 'Delivered to Sundarban') {
                                            echo '<div class="ep_badge bg_dark text_dark">Delivered to Sundarban</div>';
                                        } elseif ($order_status == 'Delivered to SteadFast') {
                                            echo '<div class="ep_badge bg_dark text_dark">Delivered to SteadFast</div>';
                                        } elseif ($order_status == 'Delivered to RedX') {
                                            echo '<div class="ep_badge bg_dark text_dark">Delivered to RedX</div>';
                                        } elseif ($order_status == 'On Hold') {
                                            echo '<div class="ep_badge bg_warning text_warning">On Hold</div>';
                                        } elseif ($order_status == 'Processing') {
                                            echo '<div class="ep_badge bg_info text_info">Processing</div>';
                                        } elseif ($order_status == 'Cancelled') {
                                            echo '<div class="ep_badge bg_danger text_danger">Cancelled</div>';
                                        }?></td>

                                        <td>    
                                            <p class="text_semi text_sm"><?php echo $order_total; ?>.00/- BDT</p>
                                        </td>

                                        <td><?php if ($order_pay_status == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Verified</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Unverified</div>';
                                        }?></td>

                                        <td><?php echo $order_pay_no; ?> <?php if ($order_pay_method == 'Cash on Delivery') { echo '<div class="ep_badge bg_info text_info">Cash on Delivery</div>'; }?></td>
                                        
                                        <td><?php echo $order_trxid; ?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <a href="invoice.php?id=<?php echo $order_id; ?>" target="_blank" class="btn_icon"><i class='bx bxs-printer'></i></a>
                                                <a href="order.php?edit=<?php echo $order_id; ?>" target="_blank" class="btn_icon"><i class='bx bxs-edit'></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
                <?php 
            }?>
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
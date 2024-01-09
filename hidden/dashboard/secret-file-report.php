<?php include('../assets/header.php'); ?>

<main>
    <div class="ep_section">
        <div class="ep_container">
            <h5 class="welcome_admin_title">Welcome <?php echo $admin_name; ?>,</h5>
            <p class="welcome_admin_msg">This is your admin panel. It will simplify your business and work.</p>
        </div>
    </div>
    
    <div class="ep_section">
        <div class="ep_container secret_container">
            <?php $order = 0;
            $piece = 0;
            $sundarban = 0;
            $redx = 0;
            $steadfast = 0;
            $office = 0;
            $distributor = 0;
            
            // fetch all order
            $select_order = "SELECT * FROM orders WHERE order_status IN ('Complete','Delivered to RedX','Delivered to Sundarban','Delivered to SteadFast','Processing','Distributor') AND payment_status = 1 ORDER BY id DESC";
            $sql_order = mysqli_query($db, $select_order);
            $num_order = mysqli_num_rows($sql_order);
            if ($num_order > 0) {
                while ($row_order = mysqli_fetch_assoc($sql_order)) {
                    $order_id = $row_order['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        $order++;
                        while ($row_order_secret = mysqli_fetch_assoc($sql_order_secret)) {
                            $product    = $row_order_secret['product'];
                            $qty        = $row_order_secret['qty'];
                            if ($product == 'Secret Files: War Edition') {
                                $piece += $qty;
                            }
                        }
                    }
                }
            }
            
            // fetch by sundarban
            $select_sundarban = "SELECT * FROM orders WHERE order_status = 'Delivered to Sundarban' AND payment_status = 1 ORDER BY id DESC";
            $sql_sundarban = mysqli_query($db, $select_sundarban);
            $num_sundarban = mysqli_num_rows($sql_sundarban);
            if ($num_sundarban > 0) {
                while ($row_sundarban = mysqli_fetch_assoc($sql_sundarban)) {
                    $order_id = $row_sundarban['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        $sundarban++;
                    }
                }
            }
            
            // fetch by redx
            $select_redx = "SELECT * FROM orders WHERE order_status = 'Delivered to RedX' AND payment_status = 1 ORDER BY id DESC";
            $sql_redx = mysqli_query($db, $select_redx);
            $num_redx = mysqli_num_rows($sql_redx);
            if ($num_redx > 0) {
                while ($row_redx = mysqli_fetch_assoc($sql_redx)) {
                    $order_id = $row_redx['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        $redx++;
                    }
                }
            }
            
            // fetch by steadfast
            $select_steadfast = "SELECT * FROM orders WHERE order_status = 'Delivered to SteadFast' AND payment_status = 1 ORDER BY id DESC";
            $sql_steadfast = mysqli_query($db, $select_steadfast);
            $num_steadfast = mysqli_num_rows($sql_steadfast);
            if ($num_steadfast > 0) {
                while ($row_steadfast = mysqli_fetch_assoc($sql_steadfast)) {
                    $order_id = $row_steadfast['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        $steadfast++;
                    }
                }
            }
            
            // fetch by office
            $select_office = "SELECT * FROM orders WHERE order_status = 'Complete' AND payment_status = 1 ORDER BY id DESC";
            $sql_office = mysqli_query($db, $select_office);
            $num_office = mysqli_num_rows($sql_office);
            if ($num_office > 0) {
                while ($row_office = mysqli_fetch_assoc($sql_office)) {
                    $order_id = $row_office['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        $office++;
                    }
                }
            }
            
            // fetch by distributor
            $select_distributor = "SELECT * FROM orders WHERE order_status = 'Distributor' AND payment_status = 1 ORDER BY id DESC";
            $sql_distributor = mysqli_query($db, $select_distributor);
            $num_distributor = mysqli_num_rows($sql_distributor);
            if ($num_distributor > 0) {
                while ($row_distributor = mysqli_fetch_assoc($sql_distributor)) {
                    $order_id = $row_distributor['id'];
                    
                    // check secret file order
                    $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                    $sql_order_secret = mysqli_query($db, $select_order_secret);
                    $num_order_secret = mysqli_num_rows($sql_order_secret);
                    if ($num_order_secret > 0) {
                        while ($row_order_secret = mysqli_fetch_assoc($sql_order_secret)) {
                            $product    = $row_order_secret['product'];
                            $qty        = $row_order_secret['qty'];
                            if ($product == 'Secret Files: War Edition') {
                                $distributor += $qty;
                            }
                        }
                    }
                }
            }?>
            <div class="ep_card">
                <h5 class="welcome_admin_title">Secret Files Summery</h5>
                <div class="ep_cardbox">
                    <p>Verified Order</p>
                    <p><?= $order ?></p>
                </div>
                <div class="ep_cardbox border_bottom p_bottom">
                    <p>Piece</p>
                    <p><?= $piece ?></p>
                </div>
                <div class="ep_cardbox">
                    <p>Sundarban</p>
                    <p><?= $sundarban ?></p>
                </div>
                <div class="ep_cardbox">
                    <p>RedX</p>
                    <p><?= $redx ?></p>
                </div>
                <div class="ep_cardbox">
                    <p>Steadfast</p>
                    <p><?= $steadfast ?></p>
                </div>
                <div class="ep_cardbox">
                    <p>Office</p>
                    <p><?= $office ?></p>
                </div>
                <div class="ep_cardbox">
                    <p>Distributor</p>
                    <p><?= $distributor ?> piece</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="ep_section">
        <div class="ep_container secret_container">
            <?php // Secret Files: War Edition
            $select = "SELECT DATE(order_date) as get_date FROM orders WHERE DATE(order_date) >= '2023-09-11' GROUP BY DATE(order_date) ORDER BY DATE(order_date) DESC";
            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $get_date = $row['get_date'];
                    
                    $get_date_txt = date('d M Y', strtotime($get_date));
                    
                    $order = 0;
                    $piece = 0;
                    $sundarban = 0;
                    $redx = 0;
                    $steadfast = 0;
                    $office = 0;
                    $distributor = 0;
                    
                    // fetch all order
                    $select_order = "SELECT * FROM orders WHERE order_status IN ('Complete','Delivered to RedX','Delivered to Sundarban','Delivered to SteadFast','Processing','Distributor') AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_order = mysqli_query($db, $select_order);
                    $num_order = mysqli_num_rows($sql_order);
                    if ($num_order > 0) {
                        while ($row_order = mysqli_fetch_assoc($sql_order)) {
                            $order_id = $row_order['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                $order++;
                                while ($row_order_secret = mysqli_fetch_assoc($sql_order_secret)) {
                                    $product    = $row_order_secret['product'];
                                    $qty        = $row_order_secret['qty'];
                                    if ($product == 'Secret Files: War Edition') {
                                        $piece += $qty;
                                    }
                                }
                            }
                        }
                    }
                    
                    // fetch by sundarban
                    $select_sundarban = "SELECT * FROM orders WHERE order_status = 'Delivered to Sundarban' AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_sundarban = mysqli_query($db, $select_sundarban);
                    $num_sundarban = mysqli_num_rows($sql_sundarban);
                    if ($num_sundarban > 0) {
                        while ($row_sundarban = mysqli_fetch_assoc($sql_sundarban)) {
                            $order_id = $row_sundarban['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                $sundarban++;
                            }
                        }
                    }
                    
                    // fetch by redx
                    $select_redx = "SELECT * FROM orders WHERE order_status = 'Delivered to RedX' AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_redx = mysqli_query($db, $select_redx);
                    $num_redx = mysqli_num_rows($sql_redx);
                    if ($num_redx > 0) {
                        while ($row_redx = mysqli_fetch_assoc($sql_redx)) {
                            $order_id = $row_redx['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                $redx++;
                            }
                        }
                    }
                    
                    // fetch by steadfast
                    $select_steadfast = "SELECT * FROM orders WHERE order_status = 'Delivered to SteadFast' AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_steadfast = mysqli_query($db, $select_steadfast);
                    $num_steadfast = mysqli_num_rows($sql_steadfast);
                    if ($num_steadfast > 0) {
                        while ($row_steadfast = mysqli_fetch_assoc($sql_steadfast)) {
                            $order_id = $row_steadfast['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                $steadfast++;
                            }
                        }
                    }
                    
                    // fetch by office
                    $select_office = "SELECT * FROM orders WHERE order_status = 'Complete' AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_office = mysqli_query($db, $select_office);
                    $num_office = mysqli_num_rows($sql_office);
                    if ($num_office > 0) {
                        while ($row_office = mysqli_fetch_assoc($sql_office)) {
                            $order_id = $row_office['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                $office++;
                            }
                        }
                    }
                    
                    // fetch by distributor
                    $select_distributor = "SELECT * FROM orders WHERE order_status = 'Distributor' AND payment_status = 1 AND DATE(order_date) = '$get_date' ORDER BY id DESC";
                    $sql_distributor = mysqli_query($db, $select_distributor);
                    $num_distributor = mysqli_num_rows($sql_distributor);
                    if ($num_distributor > 0) {
                        while ($row_distributor = mysqli_fetch_assoc($sql_distributor)) {
                            $order_id = $row_distributor['id'];
                            
                            // check secret file order
                            $select_order_secret = "SELECT * FROM order_details WHERE order_no = '$order_id' AND product = 'Secret Files: War Edition'";
                            $sql_order_secret = mysqli_query($db, $select_order_secret);
                            $num_order_secret = mysqli_num_rows($sql_order_secret);
                            if ($num_order_secret > 0) {
                                while ($row_order_secret = mysqli_fetch_assoc($sql_order_secret)) {
                                    $product    = $row_order_secret['product'];
                                    $qty        = $row_order_secret['qty'];
                                    if ($product == 'Secret Files: War Edition') {
                                        $distributor += $qty;
                                    }
                                }
                            }
                        }
                    }?>
                    <div class="ep_card">
                        <h5 class="welcome_admin_title"><?= $get_date_txt ?></h5>
                        <div class="ep_cardbox">
                            <p>Verified Order</p>
                            <p><?= $order ?></p>
                        </div>
                        <div class="ep_cardbox border_bottom p_bottom">
                            <p>Piece</p>
                            <p><?= $piece ?></p>
                        </div>
                        <div class="ep_cardbox">
                            <p>Sundarban</p>
                            <p><?= $sundarban ?></p>
                        </div>
                        <div class="ep_cardbox">
                            <p>RedX</p>
                            <p><?= $redx ?></p>
                        </div>
                        <div class="ep_cardbox">
                            <p>Steadfast</p>
                            <p><?= $steadfast ?></p>
                        </div>
                        <div class="ep_cardbox">
                            <p>Office</p>
                            <p><?= $office ?></p>
                        </div>
                        <div class="ep_cardbox">
                            <p>Distributor</p>
                            <p><?= $distributor ?></p>
                        </div>
                    </div>
                    <?php 
                }
            }?>
        </div>
    </div>
</main>

<?php include('../assets/footer.php'); ?>
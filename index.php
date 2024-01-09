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
    
    <!--=========== FONTS ===========-->
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="hidden/images/fav_icon.png">

    <!--=========== BOX ICON ===========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="css/style.css">

    <title>Elpandora</title>
</head>
<body>

<!--=========== MOBILE ADVERTISEMENT ===========-->
<div class="ep_advertise mb_75 mobile_ad">
    <img src="hidden/images/ad2.jpg" alt="">
</div>

<?php $cart_alert = '';
$cart_count = 0;
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
        $cart_alert = "<div class='ep_alert alert_success'>আপনার প্রোডাক্টটি সফলভাবে সংগ্রহ লিস্টে যোগ হয়েছে। <a href='cart.php'>সংগ্রহের পেইজে দেখুন <i class='bx bx-right-arrow-alt'></i></a></div>";

        if (in_array($_POST['product_id'], $session_array_id)) {
            echo '<script>window.location.href = "https://elpandorapub.com/";</script>';
        }
    } else {
        $_SESSION['cart'][0] = array(
            'id' => $_POST['product_id'],
            'name' => $_POST['product_name'],
            'price' => $_POST['product_price'],
            'qty' => $_POST['product_qty'],
        );

        $cart_alert = "<div class='ep_alert alert_success'>আপনার প্রোডাক্টটি সফলভাবে সংগ্রহ লিস্টে যোগ হয়েছে। <a href='cart.php'>সংগ্রহের পেইজে দেখুন <i class='bx bx-right-arrow-alt'></i></a></div>";
    }
}

if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}?>

<!--=========== HEADER ===========-->
<header>
    <nav class="ep_container">
        <a href="https://elpandorapub.com/">
            <img src="hidden/images/logo.png" alt="" class="lg_logo">
            <img src="hidden/images/fav_icon.png" alt="" class="sm_logo">
        </a>

        <form action="" method="post" class="dislay_lg search_form">
            <input type="text" name="search" id="" placeholder="প্রোডাক্টের নাম লিখুন">
            <button type="submit" name="search_btn">খুঁজে দেখুন</button>
        </form>

        <ul>
            <a href="cart.php">
                <li>
                    <i class='bx bxs-shopping-bag'></i> আমার সংগ্রহ <span style="background: red; color: #FFF; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-size: 14px; font-weight: 700;"><?= $cart_count ?></span>
                </li>
            </a>
        </ul>
    </nav>
</header>

<form action="" method="post" class="dislay_sm search_form ep_container">
    <input type="text" name="search" id="" placeholder="সার্চ করুন">
    <button type="submit" name="search_btn"><i class='bx bx-search'></i></button>
</form>

<!--=========== CART ALERT ===========-->
<div class="ep_container">
    <?php echo $cart_alert; ?>
</div>

<!--=========== ADVERTISEMENT ===========-->
<div class="ep_advertise mb_75 mt_75 lg_ad">
    <img src="hidden/images/ad2.jpg" alt="">
</div>

<!--=========== HOW TO ORDER ===========-->
<div class="ep_container howorder mb_75 mt_75">
    কিভাবে অর্ডার করবেন, জানতে <a href="https://fb.watch/klVAlhuxji/">ক্লিক করুন <i class='bx bx-right-arrow-alt'></i></a>
</div>

<!--=========== TRACKING SECTION ===========-->
<div class="ep_container ep_track mb_75 mt_75">
    <div class="track_title">আপনার অর্ডারটি ট্র্যাক করুন</div>
    <form action="track-order.php" method="post">
        <input type="number" name="track_id" id="" placeholder="আপনার অর্ডার আইডি লিখুন">
        <button type="submit" name="track">Click Here</button>
    </form>
</div>

<!--=========== ALL PRODUCT ===========-->
<div class="product_container ep_container ep_grid">
    <?php $select_product = "SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT 8";
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
            $product_featured       = $row_product['featured'];
            ?>
            <!--=========== PRODUCT CARD ===========-->
            <form method="post" action="" class="product_card">
                <div class="product_card_img position_relative">
                    <a href="<?php if ($product_id == 11) {
                            echo 'sf.php';
                        } else {
                            echo 'single-product.php?product='.$product_id;
                        }?>">
                        <img src="hidden/products/<?php echo $product_image; ?>" alt="">
                    </a>
                    <div class="product_lebels">
                        <?php if ($product_stock == 1) {
                            if ($product_featured == 1) {
                                echo '<div class="ep_lebel bg-info">Featured</div>';
                            }

                            if (!empty($product_sale)) {
                                $off = ceil(100 - (($product_sale / $product_price) * 100));
                                echo '<div class="ep_lebel bg-success">'.$off.'% OFF</div>';
                            }
                        } else {
                            echo '<div class="ep_lebel bg-danger">Out of Stock</div>';
                        }?>
                    </div>
                </div>
                <div class="product_card_data text_center">
                    <p class="product_card_title"><?php echo $product_name; ?></p>
                    <div class="product_card_price text_semi">
                    <?php if (empty($product_sale)) {
                        echo '<p class="text_semi">'.$product_price.'.00/- BDT</p></td>';
                    } else {
                        echo '<span class="text_sm text_strike text-danger">'.$product_price.'.00/- BDT</span><br>
                        <p class="text_semi">'.$product_sale.'.00/- BDT</p>';
                    }?></div>
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                    <input type="hidden" name="product_price" value="<?php if (empty($product_sale)) {
                        echo $product_price;
                    } else {
                        echo $product_sale;
                    }?>">
                    <input type="hidden" name="product_qty" value="1">
                    
                    <div class="btn_grp">
                        <?php if ($product_stock == 1) {
                            echo '<button type="submit" name="add_cart" class="m_auto">সংগ্রহ করুন</button>';
                        }
                        
                        if ($product_id == 11) {
                            echo '<a href="sf.php" class="button view_details_btn m_auto">View Details</a>>';
                        } else {
                            echo '<a href="single-product.php?product='.$product_id.'" class="button view_details_btn m_auto">View Details</a>>';
                        }?>
                    </div>
                </div>
            </form>
            <?php 
        }
    }?>
</div>

<!--=========== FOOTER ===========-->
<footer>
    <div class="footer ep_container ep_grid">
        <div>
            <a href="https://elpandorapub.com/">
                <img src="hidden/images/logo.png" alt="" class="footer_logo">
            </a>
            <div class="ro">
                <div class="col1">
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
                <div class="col2">
                    <div class="tagline">Delivery Partner</div>
                    <div class="img">
                        <img src="nc_assets/img/scs.png" title="sundarban">
                        <!-- <img src="nc_assets/img/scs-white.png" title="sundarban"> -->
                        <img src="nc_assets/img/redx.png" title="red-x">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright ep_container">
        ELPANDORA &copy; COPYRIGHT 2023 DEVELOPED BY MEHEDI HASAN.
    </div>
</footer>

<style>
    .col1, .col2 {
        width: 50%;
        box-sizing: border-box;
        float: left;
    }

    .col2 {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: flex-end;
    }

    .col2 .tagline {
        text-align: right;
    }

    .ro .img img {
        max-width: 100%;
        height: 70px;
    }

    @media screen and (max-width: 567px) {
        .col1, .col2 {
            width: 100%;
            box-sizing: border-box;
            float: left;
        }
        .col2 {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
        }
    }

</style>
</body>
</html>
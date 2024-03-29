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
    <link rel="stylesheet" href="fonts/stylesheet.css">
    
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="hidden/images/fav_icon.png">

    <!--=========== BOX ICON ===========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="css/style.css">

    <title>Elpandora - Tracking</title>
</head>
<body>

<!--=========== HEADER ===========-->
<header>
    <nav class="ep_container">
        <a href="index.php">
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

<?php if (isset($_POST['track'])) {
    $track_id = $_POST['track_id'];

    // match order
    $select_track   = "SELECT * FROM orders WHERE id = '$track_id'";
    $sql_track      = mysqli_query($db, $select_track);
    $num_track      = mysqli_num_rows($sql_track);
    if ($num_track == 0) {
        ?>
        <!--=========== TRACK SECTION ===========-->
        <div class="ep_container track_container mb_75 mt_75">
            <div class="track_img">
                <img src="hidden/images/tracking.png" alt="" class="">
            </div>
            <div class="empty_track">দুঃখিত! কোনো ট্র্যাকিং খুজে পাওয়া যায়নি।</div>
        </div>
        <?php 
    } else {
        $row_track      = mysqli_fetch_assoc($sql_track);
        $order_status   = $row_track['order_status'];
        $pay_status     = $row_track['payment_status'];
        ?>
        <!--=========== TRACK NUMBER ===========-->
        <div class="ep_container mb_75 mt_75">
            <div class="tracking_title">ORDER NO. #<?php echo $track_id; ?></div>
        </div>

        <?php if ($order_status == 'Cancelled') {
            ?>
            <!--=========== CANCELLED ORDER TRACK ===========-->
            <div class="ep_container track_container mb_75 mt_75">
                <div class="track_img">
                    <img src="hidden/images/cancelled.png" alt="" class="">
                </div>
                <div class="empty_track">দুঃখিত! আপনার অর্ডারটি বাতিল করা হয়েছে।</div>
            </div>
            <?php 
        } else {
            ?>
            <!--=========== TRACK SECTION ===========-->
            <div class="ep_container track_line_container mb_75 mt_75">
                <div class="track_card">
                    <div class="track_line_img bg_primary">
                        <img src="hidden/images/order.png" alt="" class="">
                    </div>
                    <p>Order Placed</p>
                </div>
                <div class="track_line <?php if ($pay_status == 1) {echo 'bg_primary';} ?>"></div>
                <div class="track_card">
                    <div class="track_line_img <?php if ($pay_status == 1) {echo 'bg_primary';} ?>">
                        <img src="hidden/images/card.png" alt="" class="">
                    </div>
                    <p>Payment Verified</p>
                </div>
                <?php if ($order_status == 'Complete') {
                    ?>
                    <div class="track_line bg_primary"></div>
                        <div class="track_card">
                            <div class="track_line_img bg_primary">
                                <img src="hidden/images/truck.png" alt="" class="">
                            </div>
                            <p>Shipping</p>
                        </div>
                        <div class="track_line bg_primary"></div>
                        <div class="track_card">
                            <div class="track_line_img bg_primary">
                                <img src="hidden/images/package.png" alt="" class="">
                            </div>
                            <p>Delivered</p>
                        </div>
                    <?php 
                } else {
                    ?>
                    <div class="track_line <?php if ($order_status == 'Delivered to Sundarban' || $order_status == 'Delivered to RedX') {echo 'bg_primary';} ?>"></div>
                        <div class="track_card">
                            <div class="track_line_img <?php if ($order_status == 'Delivered to Sundarban' || $order_status == 'Delivered to RedX') {echo 'bg_primary';} ?>">
                                <img src="hidden/images/truck.png" alt="" class="">
                            </div>
                            <p>Shipping</p>
                        </div>
                        <div class="track_line"></div>
                        <div class="track_card">
                            <div class="track_line_img">
                                <img src="hidden/images/package.png" alt="" class="">
                            </div>
                            <p>Delivered</p>
                        </div>
                    <?php 
                }?>
            </div>
            <?php 
        }
    }
} else {
    ?>
    <!--=========== TRACK SECTION ===========-->
    <div class="ep_container track_container mb_75 mt_75">
        <div class="track_img">
            <img src="hidden/images/tracking.png" alt="" class="">
        </div>
        <div class="empty_track">দুঃখিত! কোনো ট্র্যাকিং খুজে পাওয়া যায়নি।</div>
    </div>
    <?php 
}?>

<!--=========== FOOTER ===========-->
<footer>
    <div class="footer ep_container ep_grid">
        <div>
            <a href="index.php">
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

</body>
</html>
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
    
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!--=========== BOX ICON ===========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="css/style.css">

    <title>Elpandora - Order Success</title>
</head>
<body>
    
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

<div class="ep_container">
    <h4 class="page_title">অর্ডার তথ্যসমূহ</h4>
</div>

<?php if (isset($_SESSION['order_id'])) {
    ?>
    <div class="ep_container">
        <div class='ep_alert alert_success'>
            আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে। আপনার অর্ডার নাম্বার <?php echo $_SESSION['order_id']; ?><br>
            আপনি আপনার পারসেলটি এই অর্ডার নাম্বার দিয়ে এই ওয়েবসাইটে ট্র্যাক করতে পারবেন।
        </div>
    </div>
    <?php 
} else {
    header('Location: index.php');
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
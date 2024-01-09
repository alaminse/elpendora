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

    <title>Elpandora - Cart</title>
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
    <h4 class="page_title">আপনার সংগ্রহের বই</h4>
</div>

<div class="ep_container ep_grid">
    <?php $cart_alert = '';
    if (isset($_GET['remove'])) {
        $remove = $_GET['remove'];
        if ($remove == 'all') {
            unset($_SESSION['cart']);
            $cart_alert = "<div class='ep_alert alert_success'>প্রোডাক্টগুলো সফলভাবে বাদ হয়েছে।</div>";
        } else {
            foreach($_SESSION['cart'] as $key => $value) {
                if ($value['id'] == $remove) {
                    unset($_SESSION['cart'][$key]);
                }
            }
            $cart_alert = "<div class='ep_alert alert_success'>প্রোডাক্টটি সফলভাবে বাদ হয়েছে।</div>";
        }
    }

    if (isset($_POST['update_product_qty'])) {
        foreach($_SESSION['cart'] as $key => $value) {
            if ($value['id'] == $_POST['update_product_id']) {
                $_SESSION['cart'][$key]['price'] = ($_SESSION['cart'][$key]['price'] / $_SESSION['cart'][$key]['qty']) * $_POST['update_product_qty'];
                $_SESSION['cart'][$key]['qty'] = $_POST['update_product_qty'];
            }
        }
        $cart_alert = "<div class='ep_alert alert_success'>আপনার প্রোডাক্টের পরিমাণ সফলভাবে পরিবর্তন হয়েছে।</div>";
    }?>

    <?php echo $cart_alert; ?>

    <div class="ep_flex ep_end">
        <a href="https://elpandorapub.com/"><button>আরও বই সংগ্রহ করুন</button></a>
    </div>
    
    <div class="cart_grid ep_grid">
        <table class="ep_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
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
                            <td><a href="cart.php?remove=<?php echo $value['id']; ?>" class="button btn_icon"><i class='bx bx-x'></i></a></td>
                        </tr>
                        <?php 
                    }
                    ?>
                    <tr>
                        <td colspan="3"></td>
                        <td><span id="total-price"><?php echo $total_price;?></span><span>.00/- BDT</span></td>
                        <td><a href="cart.php?remove=all" class="button btn_icon">সব বাদ দিন</a></td>
                    </tr>
                    <?php 
                } else {
                    echo "<tr><td colspan='5' class='text_center'>আপনার সংগ্রহে কোনো বই নেই।</td></tr>";
                }?>
            </tbody>
        </table>
        <?php if (!empty($_SESSION['cart'])) {
            echo '<a href="checkout.php"><button>অর্ডারটি সম্পন্ন করুন</button></a>';
        }?>
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

</body>
</html>
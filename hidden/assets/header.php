<?php include('../db/db.php');
session_start();
// session_destroy();
if (empty($_COOKIE['admin_id'])) {
    ?>
    <script type="text/javascript">
        window.location.href = '../index.php';
    </script>
    <?php 
}

// set local time zone
date_default_timezone_set('Asia/Dhaka');

include('../assets/variable.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Montserrat+Alternates:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!--=========== DATATABLE ===========-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">
    
    <!--=========== BOX ICON ===========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../images/fav_icon.png">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="../css/style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="../css/dashboard.css">

    <title>Elpandora - Admin</title>
</head>
<body>
    
<header>
    <nav>
        <a href="index.php">
            <img src="../images/logo.png" alt="">
        </a>

        <ul>
            <a href="https://elpandorapub.com/hidden/dashboard/">
                <li>
                    <i class='bx bxs-dashboard'></i> Overview
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/order.php">
                <li>
                    <i class='bx bxs-shopping-bag'></i> Order
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/products.php">
                <li>
                    <i class='bx bxs-package'></i> Products
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/product-category.php">
                <li>
                    <i class='bx bxs-category'></i> Category
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/transaction.php">
                <li>
                    <i class='bx bxs-credit-card'></i> Transaction
                </li>
            </a>
            
            <a href="https://elpandorapub.com/hidden/dashboard/deliver-report.php">
                <li>
                    <i class='bx bx-line-chart'></i> Delivery Report
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/report.php">
                <li>
                    <i class='bx bx-line-chart'></i> Report
                </li>
            </a>
            
            <a href="https://elpandorapub.com/hidden/dashboard/secret-file-report.php">
                <li>
                    <i class='bx bx-line-chart'></i> Secret Files
                </li>
            </a>
            
            <a href="https://elpandorapub.com/hidden/dashboard/gift-invoice.php">
                <li>
                    <i class='bx bx-line-chart'></i> Gift Invoice
                </li>
            </a>

            <a href="https://elpandorapub.com/hidden/dashboard/user.php">
                <li>
                    <i class='bx bxs-user' ></i> Users
                </li>
            </a>
            <a href="https://elpandorapub.com/hidden/dashboard/count.php">
                <li>
                    <i class='bx bxs-user' ></i> Count
                </li>
            </a>
        </ul>
    </nav>

    <ul>
        <a href="logout.php">
            <li>
                <i class='bx bx-log-out'></i> Log out
            </li>
        </a>
    </ul>
</header>
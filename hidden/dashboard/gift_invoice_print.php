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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        /* Define styles for the printed page */
        body {
            font-family: Arial, sans-serif;
            font-size: 20px;
        }
        .invoice-header {
            text-align: left;
            margin-bottom: 20px;
        }
        .invoice-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .invoice-address {
            margin-top: 10px;
        }
        .invoice-phone {
            margin-top: 5px;
        }
        .customer-details {
            display: grid;
            text-align: right;
        }
        .customer-info {
            flex-grow: 1;
        }
        .invoice-content {
            margin-top: 20px;
        }
    </style>

    <title>Elpandora</title>
</head>
<body>

<?php if (isset($_POST['add'])) {
    $name = $_POST['customer_name'];
    $phone = $_POST['customer_phone'];
    $address = $_POST['customer_address'];
    $status = $_POST['shipping_method'];
    $purpose = $_POST['invoice_reason'];
    $invoice_date = $_POST['invoice_date'];
    $add = "INSERT INTO invoice (name, phone, address, status, purpose, invoice_date) VALUES ('$name', '$phone', '$address', '$status', '$purpose', '$invoice_date')";
    $sql = mysqli_query($db, $add);
    if ($sql) {
        ?>
        <div class="container">
            <div class="row align-items-center">
                <div class="invoice-header col-6">
                    <h2>Elpandora</h2>
                    <p class="invoice-address">Farmgate, Dhaka</p>
                    <p class="invoice-phone">01716598030</p>
                    <p>Shipping to <?= $status ?></p>
                </div>
                <div class="customer-details col-6">
                    <h4>Shipping to</h4>
                    <div class="invoice-content">
                        <p><?= $invoice_date ?></p>
                    </div>
                    <div class="customer-info">
                        <p><?= $name ?></p>
                        <p><?= $address ?></p>
                        <p><?= $phone ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    }
}?>


<script>
    // window.print();
</script>

</body>
</html>
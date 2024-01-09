<?php include('hidden/db/db.php');
session_start();

// get location and payment method
$_SESSION['districtValue'] = $_POST['districtValue'];
$_SESSION['payMethodValue'] = $_POST['payMethodValue'];

// subtotal price and total product quantity
$total_price = 0;
$total_qty = 0;
if (!empty($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $key => $value) {
        $total_qty              = $total_qty + $value['qty'];
        $total_price            = $total_price + $value['price'];

        $_SESSION['total_qty']  = $total_qty;
        $_SESSION['subtotal']   = $total_price;
    }
}

// build logic according to district 
if ($_SESSION['districtValue'] == 'Dhaka') {
    // if location is Dhaka, delivery charge fixed 60 taka
    $_SESSION['delivery_charge'] = (50 + ($total_qty * 20));
    $_SESSION['delivery_text'] = '<p class="ep_flex"><span class="text_semi">Delivery Charge: </span><span>'.(50 + ($total_qty * 20)).'.00/- BDT</span></p>';
    echo '<p class="ep_flex"><span class="text_semi">Delivery Charge: </span><span>'.(50 + ($total_qty * 20)).'.00/- BDT</span></p>';

    $subintotal = $_SESSION['subtotal'] + $_SESSION['delivery_charge'];
    $_SESSION['subintotal'] = $subintotal;

    if ($_SESSION['payMethodValue'] == 'Bkash') {
        // if method is bkash, transaction charge will be 1.5%
        $_SESSION['transaction_charge'] = floor($subintotal * 0);
        $transaction_charge = $_SESSION['transaction_charge'];
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Bkash Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Bkash Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
    } elseif ($_SESSION['payMethodValue'] == 'Nagad') {
        // if method is nagad, transaction charge will be 1.2%
        $_SESSION['transaction_charge'] = floor($subintotal * 0);
        $transaction_charge = $_SESSION['transaction_charge'];
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Nagad Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Nagad Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
    } else {
        // if method is cash or cash on delivery, no transaction charge
        $_SESSION['transaction_charge'] = 0;
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Transaction Charge: </span><span>0.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Transaction Charge: </span><span>0.00/- BDT</span></p>';
    }

    // calculate grand total payment according to all logic
    $_SESSION['grand_total'] = $_SESSION['subintotal'] + $_SESSION['transaction_charge'];
    $grand_total = $_SESSION['grand_total'];

    $_SESSION['grand_total_text'] = '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$grand_total.'.00/- BDT</span></p>';

    echo '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$grand_total.'.00/- BDT</span></p>';
} else {
    // if location is no Dhaka, courier charge increase by product quantity
    $_SESSION['delivery_charge'] = (45 + ($total_qty * 15));
    $_SESSION['delivery_text'] = '<p class="ep_flex"><span class="text_semi">Courier Charge: </span><span>'.(45 + ($total_qty * 15)).'.00/- BDT</span></p>';
    echo '<p class="ep_flex"><span class="text_semi">Courier Charge: </span><span>'.(45 + ($total_qty * 15)).'.00/- BDT</span></p>';

    $subintotal = $_SESSION['subtotal'] + $_SESSION['delivery_charge'];
    $_SESSION['subintotal'] = $subintotal;

    if ($_SESSION['payMethodValue'] == 'Bkash') {
        // if method is bkash, transaction charge will be 1.5%
        $_SESSION['transaction_charge'] = floor($subintotal * 0);
        $transaction_charge = $_SESSION['transaction_charge'];
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Bkash Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Bkash Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
    } elseif ($_SESSION['payMethodValue'] == 'Nagad') {
        // if method is nagad, transaction charge will be 1.2%
        $_SESSION['transaction_charge'] = floor($subintotal * 0);
        $transaction_charge = $_SESSION['transaction_charge'];
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Nagad Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Nagad Charge: </span><span>'.$transaction_charge.'.00/- BDT</span></p>';
    } else {
        // if method is cash or cash on delivery, no transaction charge
        $_SESSION['transaction_charge'] = 0;
        $_SESSION['transaction_text'] = '<p class="ep_flex"><span class="text_semi">Transaction Charge: </span><span>0.00/- BDT</span></p>';
        echo '<p class="ep_flex"><span class="text_semi">Transaction Charge: </span><span>0.00/- BDT</span></p>';
    }

    // calculate grand total payment according to all logic
    $_SESSION['grand_total'] = $_SESSION['subintotal'] + $_SESSION['transaction_charge'];
    $grand_total = $_SESSION['grand_total'];

    $_SESSION['grand_total_text'] = '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$grand_total.'.00/- BDT</span></p>';

    echo '<p class="ep_flex text_h5"><span class="text_semi">Grand Total: </span><span class="text_semi">'.$grand_total.'.00/- BDT</span></p>';
}?>
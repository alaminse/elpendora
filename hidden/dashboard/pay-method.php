<?php include('../db/db.php');
session_start();

// build logic according to district 
if ($_POST['districtValue'] == 'Dhaka') {
    echo '<option>Choose Method</option>
        <option value="Cash">Cash</option>
        <option value="Bkash">Bkash</option>
        <option value="Nagad">Nagad</option>
        <option value="Cash on Delivery">Cash on Delivery</option>';
} else {
    echo '<option>Choose Method</option>
        <option value="Bkash">Bkash</option>
        <option value="Nagad">Nagad</option>';
}?>
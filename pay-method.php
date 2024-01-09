<?php include('hidden/db/db.php');
session_start();

// build logic according to district 
if ($_POST['districtValue'] == 'Dhaka') {
    echo '<option value="">Choose Method</option>
        <option value="Bkash">Bkash</option>
        <option value="Nagad">Nagad</option>
        <option value="Cash on Delivery">Cash on Delivery</option>';
} else {
    echo '<option value="">Choose Method</option>
        <option value="Bkash">Bkash</option>
        <option value="Nagad">Nagad</option>';
}?>
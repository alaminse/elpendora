<?php include('hidden/db/db.php');
session_start();

// build logic according to district 
if ($_POST['payMethodValue'] == 'Bkash' || $_POST['payMethodValue'] == 'Nagad') {
    echo '<div>
            <label>যেই নাম্বারে টাকা পাঠাবেনঃ</label>
            <p>বিকাশঃ 01722667141 (পেমেন্ট)</p>
            <p>নগদঃ 01320793710 (সেন্ড মানি)</p>
        </div>

        <div>
            <label for="pay-no">পেমেন্টের নাম্বার</label>
            <input type="text" id="pay-no" name="pay_no" placeholder="Payment Number">
        </div>

        <div>
            <label for="trx-id">ট্রান্সেকশন আইডি</label>
            <input type="text" id="trx-id" name="trx_id" placeholder="Transaction ID">
        </div>';
} else {
    echo '';
}?>
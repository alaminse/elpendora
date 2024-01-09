<?php include('../assets/header.php'); 
$today = date('Y-m-d', time());?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">Reports</h4>
        </div>
    </div>

    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container report_boxs">
            <div class="report_box">
                <p class="report_box_title">Total Net Sell</p>
                <p class="report_box_data">
                    <?php $select_total_sell = "SELECT sum(payment_amount) as total_sell FROM orders WHERE order_status NOT IN ('Cancelled')";
                    $sql_total_sell = mysqli_query($db, $select_total_sell);
                    $row_total_sell = mysqli_fetch_assoc($sql_total_sell);
                    if (!empty($row_total_sell['total_sell'])) {
                        $total_sell = $row_total_sell['total_sell'];
                        echo $total_sell.'/- BDT';
                    } else {
                        $total_sell = 'N/A';
                        echo $total_sell;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Net Sell</p>
                <p class="report_box_data">
                    <?php $select_sell = "SELECT sum(payment_amount) as sell FROM orders WHERE DATE(order_date) = '$today' AND order_status NOT IN ('Cancelled')";
                    $sql_sell = mysqli_query($db, $select_sell);
                    $row_sell = mysqli_fetch_assoc($sql_sell);
                    if (!empty($row_sell['sell'])) {
                        $sell = $row_sell['sell'];
                        echo $sell.'/- BDT';
                    } else {
                        $sell = 'N/A';
                        echo $sell;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Expense</p>
                <p class="report_box_data">
                    <?php $select_expense = "SELECT sum(total) as expense FROM transaction WHERE DATE(issued_date) = '$today' AND type = 2";
                    $sql_expense = mysqli_query($db, $select_expense);
                    $row_expense = mysqli_fetch_assoc($sql_expense);
                    if (!empty($row_expense['expense'])) {
                        $expense = $row_expense['expense'];
                        echo $expense.'/- BDT';
                    } else {
                        $expense = 'N/A';
                        echo $expense;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Cash Out</p>
                <p class="report_box_data">
                    <?php $select_cashout = "SELECT sum(total) as cashout FROM transaction WHERE DATE(cashout_date) = '$today' AND type = 1";
                    $sql_cashout = mysqli_query($db, $select_cashout);
                    $row_cashout = mysqli_fetch_assoc($sql_cashout);
                    if (!empty($row_cashout['cashout'])) {
                        $cashout = $row_cashout['cashout'];
                        echo $cashout.'/- BDT';
                    } else {
                        $cashout = 'N/A';
                        echo $cashout;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Bkash</p>
                <p class="report_box_data">
                    <?php $select_bkash = "SELECT sum(payment_amount) as bkash FROM orders WHERE payment_method = 'Bkash' AND DATE(order_date) = '$today' AND order_status NOT IN ('Cancelled')";
                    $sql_bkash = mysqli_query($db, $select_bkash);
                    $row_bkash = mysqli_fetch_assoc($sql_bkash);
                    if (!empty($row_bkash['bkash'])) {
                        $bkash = $row_bkash['bkash'];
                        echo $bkash.'/- BDT';
                    } else {
                        $bkash = 'N/A';
                        echo $bkash;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Nagad</p>
                <p class="report_box_data">
                    <?php $select_nagad = "SELECT sum(payment_amount) as nagad FROM orders WHERE payment_method = 'Nagad' AND DATE(order_date) = '$today' AND order_status NOT IN ('Cancelled')";
                    $sql_nagad = mysqli_query($db, $select_nagad);
                    $row_nagad = mysqli_fetch_assoc($sql_nagad);
                    if (!empty($row_nagad['nagad'])) {
                        $nagad = $row_nagad['nagad'];
                        echo $nagad.'/- BDT';
                    } else {
                        $nagad = 'N/A';
                        echo $nagad;
                    }?>
                </p>
            </div>
            <div class="report_box">
                <p class="report_box_title">Cash</p>
                <p class="report_box_data">
                    <?php $select_cash = "SELECT sum(payment_amount) as cash FROM orders WHERE payment_method = 'Cash' AND DATE(order_date) = '$today' AND order_status NOT IN ('Cancelled')";
                    $sql_cash = mysqli_query($db, $select_cash);
                    $row_cash = mysqli_fetch_assoc($sql_cash);
                    if (!empty($row_cash['cash'])) {
                        $cash = $row_cash['cash'];
                        echo $cash.'/- BDT';
                    } else {
                        $cash = 'N/A';
                        echo $cash;
                    }?>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/footer.php'); ?>
<?php include('../assets/header.php'); ?>

<?php $alert = '';
if (isset($_POST['add_trx'])) {
    $reference  = mysqli_escape_string($db, $_POST['reference']);
    $total      = mysqli_escape_string($db, $_POST['total']);
    $pay_no     = mysqli_escape_string($db, $_POST['pay_no']);
    $trx_id     = mysqli_escape_string($db, $_POST['trx_id']);
    $method     = $_POST['method'];
    $type       = 2;
    $status     = 1;

    $issued_date = date('Y-m-d H:i:s', time());

    if (empty($reference) || empty($total) || empty($method)){
        $alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
    } else {
        if ($method == 'Bkash' || $method == 'Nagad') {
            if (empty($pay_no) || empty($trx_id)) {
                $alert = "<div class='alert alert-warning'>Give Payment Number And Transaction ID.....</div>";
            } else {
                // fetch duplicate trx id
                $fetch_trx  = "SELECT * FROM orders WHERE trx_id = '$trx_id'";
                $sql_trx    = mysqli_query($db, $fetch_trx);
                $num_trx    = mysqli_num_rows($sql_trx);
                if ($num_trx > 0) {
                    $alert = "<div class='alert alert-danger'>This transactyion id has already taken.....</div>";
                } else {
                    // insert expense
                    $insert_expense = "INSERT INTO transaction (method, pay_no, trxid, total, type, status, reference, issued_date) VALUES ('$method', '$pay_no', '$trx_id', '$total', '$type', '$status', '$reference', '$issued_date')";
                    if (mysqli_query($db, $insert_expense)) {
                        header("Location: transaction.php");
                    }
                }
            }
        } else {
            // insert expense
            $insert_expense = "INSERT INTO transaction (method, total, type, status, reference, issued_date) VALUES ('$method', '$total', '$type', '$status', '$reference', '$issued_date')";
            if (mysqli_query($db, $insert_expense)) {
                header("Location: transaction.php");
            }
        }
    }
}?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">All Transaction</h4>
        </div>
    </div>

    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <?php if (isset($_GET['add'])) {
                ?>
                <h5 class="box_title">Add Transaction</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form">
                    <div>
                        <label for="method">Method</label>
                        <select name="method" id="method">
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                        </select>
                    </div>
                    <div>
                        <label for="reference">Reference</label>
                        <input type="text" name="reference" id="reference" placeholder="Expence Reference">
                    </div>
                    <div>
                        <label for="total">Total</label>
                        <input type="text" name="total" id="total" placeholder="Total Expence">
                    </div>
                    <div>
                        <label for="pay_no">Payment Number</label>
                        <input type="text" name="pay_no" id="pay_no" placeholder="Payment Number">
                    </div>
                    <div>
                        <label for="trx_id">Transaction ID</label>
                        <input type="text" name="trx_id" id="trx_id" placeholder="Transaction ID">
                    </div>

                    <button type="submit" name="add_trx">Submit</button>
                </form>
                <?php 
            } elseif (isset($_GET['edit'])) {
                $edit = $_GET['edit'];

                // fetch transaction details
                $fetch_transaction = "SELECT * FROM transaction WHERE id = '$edit'";
                $sql_fetch_transaction = mysqli_query($db, $fetch_transaction);
                $row_fetch_transaction = mysqli_fetch_assoc($sql_fetch_transaction);
                $type       = $row_fetch_transaction['type'];
                $status     = $row_fetch_transaction['status'];
                $pay_no     = $row_fetch_transaction['pay_no'];
                $trx_id     = $row_fetch_transaction['trxid'];
                $total      = $row_fetch_transaction['total'];
                $reference  = $row_fetch_transaction['reference'];

                if (isset($_POST['update_in'])) {
                    $update_status = $_POST['update_status'];

                    $cashout_date = date('Y-m-d H:i:s', time());

                    // update earn transaction
                    $update_in      = "UPDATE transaction SET status = '$update_status', cashout_date = '$cashout_date' WHERE id = '$edit'";
                    $update_order   = "UPDATE orders SET payment_status = '$update_status' WHERE id = '$reference'";
                    if (mysqli_query($db, $update_in) && mysqli_query($db, $update_order)) {
                        header("Location: transaction.php");
                    }
                }

                $edit_alert = '';
                if (isset($_POST['update_out'])) {
                    $update_total   = mysqli_escape_string($db, $_POST['update_total']);
                    $update_pay_no  = mysqli_escape_string($db, $_POST['update_pay_no']);
                    $update_trx_id  = mysqli_escape_string($db, $_POST['update_trx_id']);

                    // update earn transaction
                    $update_in = "UPDATE transaction SET pay_no = '$update_pay_no', trxid = '$update_trx_id', total = '$update_total' WHERE id = '$edit'";
                    if (mysqli_query($db, $update_in)) {
                        header("Location: transaction.php");
                    }
                }

                if ($type == 1) {
                    ?>
                    <h5 class="box_title">Update Transaction</h5>

                    <form action="" method="post">
                        <label for="status">Status</label>
                        <select name="update_status" id="status">
                            <option value="0" <?php if ($status == 0) {echo 'selected';}?>>Unverified</option>
                            <option value="1" <?php if ($status == 1) {echo 'selected';}?>>Verified</option>
                        </select>
                        <button type="submit" name="update_in">Submit</button>
                    </form>
                    <?php 
                } elseif ($type == 2) {
                    ?>
                    <h5 class="box_title">Update Transaction</h5>

                    <?php echo $edit_alert; ?>

                    <form action="" method="post" class="double_col_form">
                        <div>
                            <label for="total">Total</label>
                            <input type="text" name="update_total" id="total" placeholder="Total Expence" value="<?php echo $total;?>">
                        </div>
                        <div>
                            <label for="pay_no">Payment Number</label>
                            <input type="text" name="update_pay_no" id="pay_no" placeholder="Payment Number" value="<?php echo $pay_no;?>">
                        </div>
                        <div>
                            <label for="trx_id">Transaction ID</label>
                            <input type="text" name="update_trx_id" id="trx_id" placeholder="Transaction ID" value="<?php echo $trx_id;?>">
                        </div>

                        <button type="submit" name="update_out">Submit</button>
                    </form>
                    <?php 
                }
            } else {
                ?>
                <!--========== MANAGE PRODUCT ==========-->
                <div class="mng_category">
                    <div class="ep_flex">
                        <h5 class="box_title">Manage Transaction</h5>
                        <a href="transaction.php?add" class="button btn_hover">Cash Out</a>
                    </div>

                    <table class="ep_table" id="orders">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Total</th>
                                <th>Payment No</th>
                                <th>Trx ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_trans = "SELECT * FROM transaction ORDER BY id DESC";
                            $sql_trans = mysqli_query($db, $select_trans);
                            $num_trans = mysqli_num_rows($sql_trans);
                            if ($num_trans > 0) {
                                while ($row_trans = mysqli_fetch_assoc($sql_trans)) {
                                    $trans_id   = $row_trans['id'];
                                    $method     = $row_trans['method'];
                                    $pay_no     = $row_trans['pay_no'];
                                    $trxid      = $row_trans['trxid'];
                                    $total      = $row_trans['total'];
                                    $type       = $row_trans['type'];
                                    $status     = $row_trans['status'];
                                    $reference  = $row_trans['reference'];
                                    $issued_date    = $row_trans['issued_date'];
                                    ?>
                                    <tr>
                                        <td><?php echo $method; ?></td>

                                        <td><?php echo $reference; ?></td>

                                        <td><?php if ($status == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Verified</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Unverified</div>';
                                        }?></td>

                                        <td class="text_sm"><?php $now = date('Y-m-d H:i:s');
                                        $read_time = date('Y-m-d H:i:s', strtotime($issued_date));

                                        $dt1 = new DateTime($read_time);
                                        $dt2 = new DateTime($now);
                                        $time_diff = $dt1->diff($dt2)->format('%r%y years, %m months, %d days, %h hours, %i minutes, %s seconds');

                                        $time_diff_y = $dt1->diff($dt2) -> format('%r%y');
                                        $time_diff_m = $dt1->diff($dt2) -> format('%r%m');
                                        $time_diff_d = $dt1->diff($dt2) -> format('%r%d');
                                        $time_diff_h = $dt1->diff($dt2) -> format('%r%h');
                                        $time_diff_i = $dt1->diff($dt2) -> format('%r%i');
                                        $time_diff_s = $dt1->diff($dt2) -> format('%r%s');

                                        if ($time_diff_y > 0) {
                                            $issued_date = date('d M Y', strtotime($issued_date));
                                            echo $issued_date;
                                        } else {
                                            if ($time_diff_m > 0) {
                                                $issued_date = date('d M Y', strtotime($issued_date));
                                                echo $issued_date;
                                            } else {
                                                if ($time_diff_d > 0) {
                                                    $issued_date = date('d M Y', strtotime($issued_date));
                                                    echo $issued_date;
                                                } else {
                                                    if ($time_diff_h > 0) {
                                                        $ago_time = $time_diff_h." hour ago";
                                                        echo $ago_time;
                                                    } else {
                                                        if ($time_diff_i > 0) {
                                                            $ago_time = $time_diff_i." min ago";
                                                            echo $ago_time;
                                                        } else {
                                                            $ago_time = "few sec ago";
                                                            echo $ago_time;
                                                        }
                                                    }
                                                }
                                            } 
                                        }?></td>

                                        <td><?php if ($type == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Earn</div>';
                                        } elseif ($type == 2) {
                                            echo '<div class="ep_badge bg_danger text_danger">Expense</div>';
                                        } elseif ($type == 3) {
                                            echo '<div class="ep_badge bg_warning text_warning">Refund</div>';
                                        }?></td>

                                        <td>    
                                            <p class="text_semi text_sm"><?php echo $total; ?>.00/- BDT</p>
                                        </td>

                                        <td><?php echo $pay_no; ?></td>
                                        
                                        <td><?php echo $trxid; ?></td>

                                        <td>
                                            <?php if ($type != 3) {
                                                ?>
                                                <div class="btn_grp">
                                                    <a href="transaction.php?edit=<?php echo $trans_id; ?>" class="btn_icon"><i class='bx bxs-edit'></i></a>
                                                </div>
                                                <?php 
                                            }?>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
$(document).ready( function () {
    $('#orders').DataTable( {
        dom: 'Bfrtip',
        order: [[3, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

<?php include('../assets/footer.php'); ?>
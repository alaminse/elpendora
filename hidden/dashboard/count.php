<?php include('../assets/header.php'); ?>

<main>
    <div class="ep_section">
        <div class="ep_container">
            <h5 class="welcome_admin_title">Welcome <?php echo $admin_name; ?>,</h5>
            <p class="welcome_admin_msg">This is your admin panel. It will simplify your business and work.</p>
        </div>
    </div>
    
    <div class="ep_section">
        <div class="ep_container">
           <?php
                $start = "2023-12-15 22:00:00";
                $start = date('Y-m-d H:i:s', strtotime($start));
                $end = "2023-12-17 11:46:00";
                $end = date('Y-m-d H:i:s', strtotime($end));

                
                $count_query = "SELECT COUNT(*) AS order_count 
                FROM `orders` o 
                INNER JOIN `order_details` od ON o.id = od.order_no 
                WHERE od.price = 2100 
                AND od.product = 'Secret Files: War Edition' 
                AND o.order_date BETWEEN '$start' AND '$end'";

                $result = mysqli_query($db, $count_query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $orderCount = $row['order_count'];
                    echo "Number of orders matching the criteria: " . $orderCount;
                } else {
                    echo "Error executing the count query: " . mysqli_error($db);
                }
                
                // $select_item = "SELECT id FROM `orders` WHERE order_date BETWEEN '$start' AND '$end'";                
                // $sql_item = mysqli_query($db, $select_item);
                
                // while ($row_item = mysqli_fetch_assoc($sql_item)) {
                //     $orderId = $row_item['id'];
                
                //     // Query to find order details with the specified conditions
                //     $select_details = "SELECT * FROM `order_details` 
                //                        WHERE order_no = '$orderId' 
                //                        AND price = 2100 
                //                        AND product = 'Secret Files: War Edition'";
                    
                //     $sql_details = mysqli_query($db, $select_details);
                    
                //     // Fetching and displaying the details
                //     while ($row_details = mysqli_fetch_assoc($sql_details)) {
                //         echo "Order ID: " . $row_item['id'] . "<br>";
                //         echo "Price: " . $row_details['price'] . "<br>";
                //         echo "Product Name: " . $row_details['product'] . "<br>";
                //         // Display other details as needed
                //     }
                // }
           ?>
        </div>
    </div>
</main>

<?php include('../assets/footer.php'); ?>
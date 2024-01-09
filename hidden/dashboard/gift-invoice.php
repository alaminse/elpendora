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
            <h4>Add Invoice</h4>
            
            <div class="invoice-form">
                <form method="POST" action="gift_invoice_print.php">
                    <label for="invoice_date">Date:</label>
                    <input type="date" id="invoice_date" name="invoice_date" required><br><br>
        
                    <label for="customer_name">Customer Name:</label>
                    <input type="text" id="customer_name" name="customer_name" required><br><br>
        
                    <label for="customer_phone">Customer Phone:</label>
                    <input type="tel" id="customer_phone" name="customer_phone"><br><br>
        
                    <label for="customer_address">Customer Address:</label>
                    <textarea id="customer_address" name="customer_address" rows="4" cols="50" required></textarea><br><br>
        
                    <label for="shipping_method">Shipping Method:</label>
                    <select id="shipping_method" name="shipping_method" required>
                        <option value="RedX">RedX</option>
                        <option value="Sundarban">Sundarban</option>
                    </select><br><br>
        
                    <label for="invoice_reason">Invoice Reason:</label>
                    <textarea id="invoice_reason" name="invoice_reason" rows="4" cols="50" required></textarea><br><br>
        
                    <input type="submit" name="add" value="Add Invoice">
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/footer.php'); ?>
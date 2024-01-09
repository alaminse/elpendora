<?php include('../db/db.php');
$subtotal = 0;
if (isset($_POST['product']) && isset($_POST['qty'])) {
    $product    = $_POST['product'];
    $qty        = $_POST['qty'];
    $select_products = "SELECT * FROM products WHERE id = '$product'";
    $sql_products = mysqli_query($db, $select_products);
    $num_products = mysqli_num_rows($sql_products);
    if ($num_products > 0) {
        $row_products = mysqli_fetch_assoc($sql_products);
        $products_id        = $row_products['id'];
        $products_name      = $row_products['name'];
        $products_price     = $row_products['price'];
        $products_sale      = $row_products['offer_price'];
        if (!empty($products_sale)) {
            $products_price     = $products_sale * $qty;
        } else {
            $products_price     = $products_price * $qty;
        }

        $subtotal = $subtotal + $products_price;

        // echo $products_name.",".$qty.",".$products_price;
        echo '<tr><td class="ep_p"><input type="text" name="product_name[]" id="get-product" class="w_100" readonly="readonly" value="'.$products_name.'"></td><td class="ep_p"><input type="text" name="product_qty[]" id="get-qty" class="w_100" readonly="readonly" value="'.$qty.'"></td><td class="ep_p"><input type="text" name="product_price[]" id="get-price" class="w_100 price" readonly="readonly" value="'.$products_price.'"></td><td class="ep_p"><input type="button" id="remove" name="remove" class="bg_danger text_danger" value="Remove"></td></tr>';
    }
}?>
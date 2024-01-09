<?php include('../assets/header.php'); ?>

<?php $alert = '';
if (isset($_POST['add_product'])) {
    $product_name       = mysqli_escape_string($db, $_POST['product_name']);
    $product_price      = mysqli_escape_string($db, $_POST['product_price']);
    $product_sale       = mysqli_escape_string($db, $_POST['product_sale']);
    $product_code       = mysqli_escape_string($db, $_POST['product_code']);
    $product_des        = mysqli_escape_string($db, $_POST['product_des']);
    $product_status     = $_POST['product_status'];
    $product_stock      = $_POST['product_stock'];
    $product_category   = $_POST['product_cat'];
    $product_img        = $_FILES['product_img']['name'];

    $product_img_temp      = $_FILES['product_img']['tmp_name'];

    if (isset($_POST['product_featured'])) {
        $product_featured = $_POST['product_featured'];
    } else {
        $product_featured = 0;
    }

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($product_name) || empty($product_price) || empty($product_code) || empty($product_category) || empty($product_img)) {
        $alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
    } else {
        if ($product_price < $product_sale) {
            $alert = "<div class='alert alert-warning'>Regular Price Should be Greater than Sale Price.....</div>";
        } else {
            $array_img = explode('.', $_FILES['product_img']['name']);
            $extension_img = end($array_img);

            if ($extension_img == 'jpg' || $extension_img == 'png') {
                if (!empty($_FILES['product_pdf']['name'])) {
                    $array_pdf = explode('.', $_FILES['product_pdf']['name']);
                    $extension_pdf = end($array_pdf);
                    if ($extension_pdf != 'pdf') {
                        $alert = "<div class='alert alert-warning'>Please Only PDF File suported.....</div>";
                        $product_pdf = null;
                    } else {
                        $product_pdf        = $_FILES['product_pdf']['name'];
                        $product_pdf_temp   = $_FILES['product_pdf']['tmp_name'];

                        $random_prev = rand(0, 999999);
                        $random = rand(0, 999999);
                        $random_next = rand(0, 999999);
                        $random_xtra = rand(0, 999999);

                        $final_pdf = "ep_".$random_prev."_".$random."_".$random_next."_".$random_xtra."_".$product_pdf;

                        move_uploaded_file($product_pdf_temp, "../sample_pdf/".$final_pdf);
                    }
                } else{
                    $final_pdf = null;
                }

                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $random_xtra = rand(0, 999999);

                $final_img = "ep_".$random_prev."_".$random."_".$random_next."_".$random_xtra."_".$product_img;

                move_uploaded_file($product_img_temp, "../products/".$final_img);

                $add_product = "INSERT INTO products (name, description, status, author, created_date, price, offer_price, stock, shortcode, product_img, product_pdf, featured, category) VALUES ('$product_name', '$product_des', '$product_status', '$admin_id', '$created_date', '$product_price', '$product_sale', '$product_stock', '$product_code', '$final_img', '$final_pdf', '$product_featured', '$product_category')";
                $sql_add_product = mysqli_query($db, $add_product);
                if ($sql_add_product) {
                    header('Location: products.php');
                }
            } else {
                $alert = "<div class='alert alert-warning'>Please Only JPG or PNG Image suported.....</div>";
            }
        }
    }
}?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">All Products</h4>
        </div>
    </div>

    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD PRODUCT ==========-->
            <?php if (isset($_GET['add'])) {
                ?>
                <div class="add_category">
                    <h5 class="box_title">Add Product</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="double_col_form product_form" enctype="multipart/form-data">
                        <div class="span_3">
                            <label for="product-name">Product Name*</label>
                            <input type="text" id="product-name" name="product_name" placeholder="Product Name">
                        </div>

                        <div>
                            <label for="product-status">Status</label>
                            <select id="product-status" name="product_status">
                                <option value="0">Choose Status</option>
                                <option value="1">Published</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-price">Product Regular Price*</label>
                            <input type="text" id="product-price" name="product_price" placeholder="Product Price">
                        </div>

                        <div>
                            <label for="product-sale">Product Sale Price</label>
                            <input type="text" id="product-sale" name="product_sale" placeholder="Product Sale Price">
                        </div>

                        <div>
                            <label for="product-code">Product Short Code*</label>
                            <input type="text" id="product-code" name="product_code" placeholder="Product Short Code">
                        </div>

                        <div>
                            <label for="product-stock">Product Inventory</label>
                            <select id="product-stock" name="product_stock">
                                <option value="0">Choose Inventory</option>
                                <option value="1">In Stock</option>
                                <option value="0">Out of Stock</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-cat">Product Category*</label>
                            <select id="product-cat" name="product_cat">
                                <option value="0">Choose Product Category</option>
                                <?php $select_product_category = "SELECT * FROM product_category WHERE status = 1 ORDER BY id DESC ";
                                $sql_product_category = mysqli_query($db, $select_product_category);
                                $num_product_category = mysqli_num_rows($sql_product_category);
                                if ($num_product_category > 0) {
                                    while ($row_product_category = mysqli_fetch_assoc($sql_product_category)) {
                                        $product_category_id     = $row_product_category['id'];
                                        $product_category_name   = $row_product_category['name'];
                                        ?>
                                        <option value="<?php echo $product_category_id; ?>"><?php echo $product_category_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <div  class="file_input">
                            <label for="product-pdf">Product Sample PDF</label>
                            <input type="file" id="product-pdf" name="product_pdf">
                        </div>

                        <div class="file_input">
                            <label for="product-img">Product Image*</label>
                            <input type="file" id="product-img" name="product_img" onchange="loadFile(event)">
                        </div>

                        <div class="file_input">
                            <img id="output" width="100" />
                        </div>

                        <div class="check_input span_3">
                            <input type="checkbox" id="product-featured" name="product_featured" value="1">
                            <label for="product-featured">Featured Product</label>
                        </div>

                        <div class="span_3">
                            <label for="product-des">Product Description</label>
                            <textarea id="product-des" name="product_des" placeholder="Product Description" rows="4"></textarea>
                        </div>

                        <button type="submit" name="add_product">Add Product</button>
                    </form>
                </div>
                <?php 
            } elseif (isset($_GET['edit'])) {
                $edit = $_GET['edit'];

                $edit_alert = '';
                $select_product = "SELECT * FROM products WHERE id = '$edit'";
                $sql_product = mysqli_query($db, $select_product);
                $row_product = mysqli_fetch_assoc($sql_product);
                $product_id         = $row_product['id'];
                $product_image      = $row_product['product_img'];
                $product_pdf_file   = $row_product['product_pdf'];
                $product_name       = $row_product['name'];
                $product_code       = $row_product['shortcode'];
                $product_stock      = $row_product['stock'];
                $product_price      = $row_product['price'];
                $product_sale       = $row_product['offer_price'];
                $product_status     = $row_product['status'];
                $product_category   = $row_product['category'];
                $product_featured   = $row_product['featured'];
                $product_des        = $row_product['description'];

                if (isset($_POST['update_product'])) {
                    $product_name       = mysqli_escape_string($db, $_POST['update_product_name']);
                    $product_price      = mysqli_escape_string($db, $_POST['update_product_price']);
                    $product_sale       = mysqli_escape_string($db, $_POST['update_product_sale']);
                    $product_code       = mysqli_escape_string($db, $_POST['update_product_code']);
                    $product_des        = mysqli_escape_string($db, $_POST['update_product_des']);
                    $product_status     = $_POST['update_product_status'];
                    $product_stock      = $_POST['update_product_stock'];
                    $product_category   = $_POST['update_product_cat'];
                    $product_img        = $_FILES['update_product_img']['name'];
                
                    $product_img_temp      = $_FILES['update_product_img']['tmp_name'];
                
                    if (isset($_POST['update_product_featured'])) {
                        $product_featured = $_POST['update_product_featured'];
                    } else {
                        $product_featured = 0;
                    }
                
                    $created_date = date('Y-m-d H:i:s', time());
                
                    if (empty($product_name) || empty($product_price) || empty($product_code) || empty($product_category)) {
                        $edit_alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
                    } else {
                        if ($product_price < $product_sale) {
                            $edit_alert = "<div class='alert alert-warning'>Regular Price Should be Greater than Sale Price.....</div>";
                        } else {
                            if (empty($product_img)) {
                                $final_img = $product_image;
                            } else {
                                $array_img = explode('.', $_FILES['update_product_img']['name']);
                                $extension_img = end($array_img);
                    
                                if ($extension_img == 'jpg' || $extension_img == 'png') {
                                    $random_prev    = rand(0, 999999);
                                    $random         = rand(0, 999999);
                                    $random_next    = rand(0, 999999);
                                    $random_xtra    = rand(0, 999999);
                    
                                    $final_img = "ep_".$random_prev."_".$random."_".$random_next."_".$random_xtra."_".$product_img;
                    
                                    move_uploaded_file($product_img_temp, "../products/".$final_img);
                                } else {
                                    $edit_alert = "<div class='alert alert-warning'>Please Only JPG or PNG Image suported.....</div>";
                                }
                            }

                            if (!empty($_FILES['update_product_pdf']['name'])) {
                                $array_pdf = explode('.', $_FILES['update_product_pdf']['name']);
                                $extension_pdf = end($array_pdf);
                                if ($extension_pdf != 'pdf') {
                                    $edit_alert = "<div class='alert alert-warning'>Please Only PDF File suported.....</div>";
                                    $product_pdf = null;
                                } else {
                                    $product_pdf        = $_FILES['update_product_pdf']['name'];
                                    $product_pdf_temp   = $_FILES['update_product_pdf']['tmp_name'];
            
                                    $random_prev = rand(0, 999999);
                                    $random = rand(0, 999999);
                                    $random_next = rand(0, 999999);
                                    $random_xtra = rand(0, 999999);
            
                                    $final_pdf = "ep_".$random_prev."_".$random."_".$random_next."_".$random_xtra."_".$product_pdf;
            
                                    move_uploaded_file($product_pdf_temp, "../sample_pdf/".$final_pdf);
                                }
                            } else{
                                echo $final_pdf = $product_pdf_file;
                            }

                            $update_product = "UPDATE products SET name = '$product_name', description = '$product_des', status = '$product_status', price = '$product_price', offer_price = '$product_sale', stock = '$product_stock', shortcode = '$product_code', product_img = '$final_img', product_pdf = '$final_pdf', featured = '$product_featured', category = '$product_category' WHERE id = '$edit'";
                            $sql_update_product = mysqli_query($db, $update_product);
                            if ($sql_update_product) {
                                header('Location: products.php');
                            }
                        }
                    }
                }?>
                <div class="add_category">
                    <h5 class="box_title">Update Product</h5>

                    <?php echo $edit_alert; ?>

                    <form action="" method="post" class="double_col_form product_form" enctype="multipart/form-data">
                        <div class="span_3">
                            <label for="product-name">Product Name*</label>
                            <input type="text" id="product-name" name="update_product_name" placeholder="Product Name" value="<?php echo $product_name; ?>">
                        </div>

                        <div>
                            <label for="product-status">Status</label>
                            <select id="product-status" name="update_product_status">
                                <option value="0">Choose Status</option>
                                <option value="1" <?php if ($product_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($product_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-price">Product Regular Price*</label>
                            <input type="text" id="product-price" name="update_product_price" placeholder="Product Price" value="<?php echo $product_price; ?>">
                        </div>

                        <div>
                            <label for="product-sale">Product Sale Price</label>
                            <input type="text" id="product-sale" name="update_product_sale" placeholder="Product Sale Price" value="<?php echo $product_sale; ?>">
                        </div>

                        <div>
                            <label for="product-code">Product Short Code*</label>
                            <input type="text" id="product-code" name="update_product_code" placeholder="Product Short Code" value="<?php echo $product_code; ?>">
                        </div>

                        <div>
                            <label for="product-stock">Product Inventory</label>
                            <select id="product-stock" name="update_product_stock">
                                <option value="0">Choose Inventory</option>
                                <option value="1" <?php if ($product_stock == 1) {echo "selected";} ?>>In Stock</option>
                                <option value="0" <?php if ($product_stock == 0) {echo "selected";} ?>>Out of Stock</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-cat">Product Category*</label>
                            <select id="product-cat" name="update_product_cat">
                                <option value="0">Choose Product Category</option>
                                <?php $select_product_category = "SELECT * FROM product_category WHERE status = 1 ORDER BY id DESC ";
                                $sql_product_category = mysqli_query($db, $select_product_category);
                                $num_product_category = mysqli_num_rows($sql_product_category);
                                if ($num_product_category > 0) {
                                    while ($row_product_category = mysqli_fetch_assoc($sql_product_category)) {
                                        $product_category_id     = $row_product_category['id'];
                                        $product_category_name   = $row_product_category['name'];
                                        ?>
                                        <option value="<?php echo $product_category_id; ?>" <?php if ($product_category == $product_category_id) {echo "selected";} ?>><?php echo $product_category_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <div  class="file_input">
                            <label for="product-pdf">Product Sample PDF</label>
                            <input type="file" id="product-pdf" name="update_product_pdf">
                        </div>

                        <div class="file_input">
                            <label for="product-img">Product Image*</label>
                            <input type="file" id="product-img" name="update_product_img" onchange="loadFile(event)">
                        </div>

                        <div class="file_input">
                            <img src="../products/<?php echo $product_image; ?>" id="output" width="100" />
                        </div>

                        <div class="check_input span_3">
                            <input type="checkbox" id="product-featured" name="update_product_featured" value="1" <?php if ($product_featured == 1) {echo "checked";} ?>>
                            <label for="product-featured">Featured Product</label>
                        </div>

                        <div class="span_3">
                            <label for="product-des">Product Description</label>
                            <textarea type="email" id="product-des" name="update_product_des" placeholder="Product Description" rows="4"><?php echo $product_des; ?></textarea>
                        </div>

                        <button type="submit" name="update_product">Update Product</button>
                    </form>
                </div>
                <?php 
            } elseif (isset($_GET['delete'])) {
                $delete = $_GET['delete'];

                $delete_product = "DELETE FROM products WHERE id = '$delete'";
                $sql_delete_product = mysqli_query($db, $delete_product);
                if ($sql_delete_product) {
                    header('Location: products.php');
                }
            } else {
                ?>
                <!--========== MANAGE PRODUCT ==========-->
                <div class="mng_category">
                    <div class="ep_flex">
                        <h5 class="box_title">Manage Product</h5>
                        <a href="products.php?add" class="button btn_hover">Add Product</a>
                    </div>

                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Featured</th>
                                <th>Released</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_product = "SELECT * FROM products ORDER BY id DESC";
                            $sql_product = mysqli_query($db, $select_product);
                            $num_product = mysqli_num_rows($sql_product);
                            if ($num_product == 0) {
                                echo "<tr><td colspan='11' class='text_center'>There are no category</td></tr>";
                            } else {
                                $si = 0;
                                while ($row_product = mysqli_fetch_assoc($sql_product)) {
                                    $product_id         = $row_product['id'];
                                    $product_image      = $row_product['product_img'];
                                    $product_name       = $row_product['name'];
                                    $product_code       = $row_product['shortcode'];
                                    $product_stock      = $row_product['stock'];
                                    $product_price      = $row_product['price'];
                                    $product_sale       = $row_product['offer_price'];
                                    $product_status     = $row_product['status'];
                                    $product_category   = $row_product['category'];
                                    $product_featured   = $row_product['featured'];
                                    $product_release    = $row_product['created_date'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>

                                        <td>
                                            <div class="product_data_img">
                                                <img src="../products/<?php echo $product_image; ?>" alt="">
                                            </div>
                                        </td>

                                        <td><?php echo $product_name; ?></td>

                                        <td class="text_sm text_semi"><?php echo $product_code; ?></td>

                                        <td><?php if ($product_stock == 1) {
                                            echo '<div class="ep_badge bg_success text_success">In Stock</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Out of Stock</div>';
                                        }?></td>
                                        <td><?php if (empty($product_sale)) {
                                            echo '<p class="text_semi">'.$product_price.'.00/- BDT</p></td>';
                                        } else {
                                            echo '<span class="text_sm text_strike">'.$product_price.'.00/- BDT</span><br>
                                            <p class="text_semi">'.$product_sale.'.00/- BDT</p></td>';
                                        }?></td>

                                        <td><?php if ($product_status == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Published</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                        }?></td>

                                        <td><?php $select_product_category = "SELECT * FROM product_category WHERE id = '$product_category'";
                                        $sql_product_category = mysqli_query($db, $select_product_category);
                                        $row_product_category = mysqli_fetch_assoc($sql_product_category);
                                        echo $row_product_category['name']; ?></td>

                                        <td><?php if ($product_featured == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Yes</div>';
                                        } else {
                                            echo '--';
                                        }?></td>

                                        <td><?php $product_release = date('d M Y', strtotime($product_release));
                                        echo $product_release; ?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <a href="products.php?edit=<?php echo $product_id; ?>" class="btn_icon"><i class='bx bxs-edit'></i></a>
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#product-delete<?php echo $product_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="product-delete<?php echo $product_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $product_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <a href="products.php?delete=<?php echo $product_id; ?>" type="button" class="button bg_danger text_danger">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

<script>
/*========== LIVE PROFILE DATA QUERY =============*/
var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};
</script>

<?php include('../assets/footer.php'); ?>
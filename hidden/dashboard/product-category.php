<?php include('../assets/header.php'); ?>

<?php $alert = '';
if (isset($_POST['add_product_category'])) {
    $product_category_name      = mysqli_escape_string($db, $_POST['product_cat_name']);
    $product_category_des       = mysqli_escape_string($db, $_POST['product_cat_des']);
    $product_category_status    = $_POST['product_cat_status'];
    $product_category_parent    = $_POST['product_cat_parent'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($product_category_name)) {
        $alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
    } else {
        $add_product_category = "INSERT INTO product_category (name, description, parent, status, author, created_date) VALUES ('$product_category_name', '$product_category_des', '$product_category_parent', '$product_category_status', '$admin_id', '$created_date')";
        $sql_add_product_category = mysqli_query($db, $add_product_category);
        if ($sql_add_product_category) {
            header('Location: product-category.php');
        }
    }
}?>

<main>
    <!--========== PAGE TITLE ==========-->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="page_title">Product Category</h4>
        </div>
    </div>

    <!--========== PAGE CONTENT ==========-->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_2">
            <?php if (isset($_GET['edit'])) {
                $edit = $_GET['edit'];

                $select_product_category = "SELECT * FROM product_category WHERE id = '$edit'";
                $sql_product_category = mysqli_query($db, $select_product_category);
                $row_product_category = mysqli_fetch_assoc($sql_product_category);
                $product_category_id        = $row_product_category['id'];
                $product_category_name      = $row_product_category['name'];
                $product_category_des       = $row_product_category['description'];
                $product_category_parent    = $row_product_category['parent'];
                $product_category_status    = $row_product_category['status'];
                $product_category_author    = $row_product_category['author'];

                if (isset($_POST['update_product_category'])) {
                    $product_category_name      = mysqli_escape_string($db, $_POST['update_product_cat_name']);
                    $product_category_des       = mysqli_escape_string($db, $_POST['update_product_cat_des']);
                    $product_category_status    = $_POST['update_product_cat_status'];
                    $product_category_parent    = $_POST['update_product_cat_parent'];
                
                    if (empty($product_category_name)) {
                        $alert = "<div class='alert alert-warning'>Please Fill Required Field.....</div>";
                    } else {
                        $update_product_category = "UPDATE product_category SET name = '$product_category_name', description = '$product_category_des', parent = '$product_category_parent', status = '$product_category_status' WHERE id = '$edit'";
                        $sql_update_product_category = mysqli_query($db, $update_product_category);
                        if ($sql_update_product_category) {
                            header('Location: product-category.php');
                        }
                    }
                }
                ?>
                <!--========== UPDATE PRODUCT CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Category</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Category Name*</label>
                            <input type="text" id="product-cat-name" name="update_product_cat_name" placeholder="Product Category Name" value="<?php echo $product_category_name; ?>">
                        </div>

                        <div>
                            <label for="product-cat-des">Category Description</label>
                            <textarea type="email" id="product-cat-des" name="update_product_cat_des" placeholder="Product Category Description" rows="4"><?php echo $product_category_des; ?></textarea>
                        </div>

                        <div>
                            <label for="product-cat-status">Status</label>
                            <select id="product-cat-status" name="update_product_cat_status">
                                <option value="">Choose Status</option>
                                <option value="1" <?php if ($product_category_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($product_category_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-cat-parent">Parent Category</label>
                            <select id="product-cat-parent" name="update_product_cat_parent">
                                <option value="">Choose Parent Category</option>
                                <?php $select_product_parent_category = "SELECT * FROM product_category";
                                $sql_product_parent_category = mysqli_query($db, $select_product_parent_category);
                                $num_product_parent_category = mysqli_num_rows($sql_product_parent_category);
                                if ($num_product_parent_category > 0) {
                                    while ($row_product_parent_category = mysqli_fetch_assoc($sql_product_parent_category)) {
                                        $product_parent_category_id     = $row_product_parent_category['id'];
                                        $product_parent_category_name   = $row_product_parent_category['name'];
                                        ?>
                                        <option value="<?php echo $product_parent_category_id; ?>" <?php if ($product_category_parent == $product_parent_category_id) {echo "selected";}?>><?php echo $product_parent_category_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <button type="submit" name="update_product_category">Update Category</button>
                    </form>
                </div>
                <?php 
            } elseif (isset($_GET['delete'])) {
                $delete = $_GET['delete'];

                $delete_product_category = "DELETE FROM product_category WHERE id = '$delete'";
                $sql_delete_product_category = mysqli_query($db, $delete_product_category);
                if ($sql_delete_product_category) {
                    header('Location: product-category.php');
                }
            } else {
                ?>
                <!--========== ADD PRODUCT CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Add Category</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Category Name*</label>
                            <input type="text" id="product-cat-name" name="product_cat_name" placeholder="Product Category Name">
                        </div>

                        <div>
                            <label for="product-cat-des">Category Description</label>
                            <textarea type="email" id="product-cat-des" name="product_cat_des" placeholder="Product Category Description" rows="4"></textarea>
                        </div>

                        <div>
                            <label for="product-cat-status">Status</label>
                            <select id="product-cat-status" name="product_cat_status">
                                <option value="">Choose Status</option>
                                <option value="1">Published</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-cat-parent">Parent Category</label>
                            <select id="product-cat-parent" name="product_cat_parent">
                                <option value="">Choose Parent Category</option>
                                <?php $select_product_parent_category = "SELECT * FROM product_category";
                                $sql_product_parent_category = mysqli_query($db, $select_product_parent_category);
                                $num_product_parent_category = mysqli_num_rows($sql_product_parent_category);
                                if ($num_product_parent_category > 0) {
                                    while ($row_product_parent_category = mysqli_fetch_assoc($sql_product_parent_category)) {
                                        $product_parent_category_id     = $row_product_parent_category['id'];
                                        $product_parent_category_name   = $row_product_parent_category['name'];

                                        echo '<option value="'.$product_parent_category_id.'">'.$product_parent_category_name.'</option>';
                                    }
                                }?>
                            </select>
                        </div>

                        <button type="submit" name="add_product_category">Add Category</button>
                    </form>
                </div>
                <?php 
            }?>

            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <h5 class="box_title">Manage Category</h5>
                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select_product_category = "SELECT * FROM product_category ORDER BY id DESC";
                        $sql_product_category = mysqli_query($db, $select_product_category);
                        $num_product_category = mysqli_num_rows($sql_product_category);
                        if ($num_product_category == 0) {
                            echo "<tr><td colspan='6' class='text_center'>There are no category</td></tr>";
                        } else {
                            $si = 0;
                            while ($row_product_category = mysqli_fetch_assoc($sql_product_category)) {
                                $product_category_id        = $row_product_category['id'];
                                $product_category_name      = $row_product_category['name'];
                                $product_category_parent    = $row_product_category['parent'];
                                $product_category_status    = $row_product_category['status'];
                                $product_category_author    = $row_product_category['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $product_category_name; ?></td>

                                    <td><?php $select_product_category_parent = "SELECT * FROM product_category WHERE id = '$product_category_parent'";
                                    $sql_product_category_parent = mysqli_query($db, $select_product_category_parent);
                                    $num_product_category_parent = mysqli_num_rows($sql_product_category_parent);
                                    if ($num_product_category_parent == 0) {
                                        echo "--";
                                    } else {
                                        $row_product_category_parent = mysqli_fetch_assoc($sql_product_category_parent);
                                        echo $row_product_category_parent['name'];
                                    }?></td>

                                    <td><?php if ($product_category_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_product_category_author = "SELECT * FROM admin WHERE id = '$product_category_author'";
                                    $sql_product_category_author = mysqli_query($db, $select_product_category_author);
                                    $num_product_category_author = mysqli_num_rows($sql_product_category_author);
                                    $row_product_category_author = mysqli_fetch_assoc($sql_product_category_author);
                                    echo $row_product_category_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <a href="product-category.php?edit=<?php echo $product_category_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#product-cat-delete<?php echo $product_category_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="product-cat-delete<?php echo $product_category_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $product_category_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <a href="product-category.php?delete=<?php echo $product_category_id; ?>" type="button" class="button bg_danger text_danger">Delete</a>
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
        </div>
    </div>
</main>

<?php include('../assets/footer.php'); ?>
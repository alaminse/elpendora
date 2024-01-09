<?php $admin_id = $_COOKIE['admin_id'];
$select_data = "SELECT * FROM admin WHERE id = '$admin_id'";
$sql = mysqli_query($db, $select_data);
$row = mysqli_fetch_assoc($sql);
$admin_name = $row['name'];
$admin_email = $row['email'];
$admin_phone = $row['phone'];
$admin_role = $row['role'];
$admin_status = $row['status'];
$admin_joined_date = $row['joined_date'];
$admin_profile = $row['profile']; ?>
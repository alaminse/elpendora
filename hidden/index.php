<?php include('db/db.php');
if (isset($_COOKIE['admin_id'])) {
    ?>
    <script type="text/javascript">
        window.location.href = 'dashboard/';
    </script>
    <?php 
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Montserrat+Alternates:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="images/fav_icon.png">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="css/style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="css/login.css">

    <title>Elpandora - Login</title>
</head>
<body>

<?php $alert = '';
if (isset($_POST['admin_login'])) {
    $user_email     = mysqli_escape_string($db, $_POST['admin_email']);
    $user_pwd       = mysqli_escape_string($db, $_POST['admin_pwd']);

    if (empty($user_email) || empty($user_pwd)) {
        $alert = "<div class='alert alert-warning'>Please Fill The All Field.....</div>";
    } else {
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $alert = "<div class='alert alert-warning'>Please Enter A Valid Email Address.....</div>";
        } else {
            $select_data = "SELECT * FROM admin WHERE email = '$user_email'";

            $sql = mysqli_query($db, $select_data);

            $num = mysqli_num_rows($sql);

            if ($num > 0) {
                if ($row = mysqli_fetch_array($sql)) {
                    $hashed_pwd = password_verify($user_pwd , $row['password']);

                    if ($hashed_pwd == false) {
                        $alert = "<div class='alert alert-warning'>The Password is Incorrect.....</div>";
                    } elseif ($hashed_pwd == true) {
                        setcookie("admin_id", $row['id'], time() + (30*24*60*60), "/");

                        header('Location: dashboard/index.php');
                    }
                } else {
                    $alert = "<div class='alert alert-warning'>Data Error.....</div>";
                }
            }
        }
    }
}?>

<form action="" method="post" class="login_form">
    <div class="login_container">
        <img src="images/logo.png" alt="">

        <div>
            <label for="email">Email address</label>
            <input type="email" id="email" name="admin_email" placeholder="Email Address">
        </div>

        <div>
            <label for="pass">Password</label>
            <input type="password" id="pass" name="admin_pwd" placeholder="Password">
        </div>

        <div class="ep_flex">
            <button type="submit" name="admin_login">Login</button>
            <a href="">Forgot Password?</a>
        </div>
    </div>
</form>

</body>
</html>
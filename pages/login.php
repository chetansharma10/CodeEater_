<?php
session_start();
include '../database/connection.php';
//initializing Variables
$username_err = $status = $password_err = "";
$err = 0;


//Validation by Regex
function validateByRegex($regex, $data)
{
    if (preg_match($regex, $data)) {
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $flag = 1;
    //Username Validation
    if (empty($_POST['username'])) {
        $flag = 0;
        $username_err = "Username Needed For LogIn ";
    } else if (!validateByRegex("/^[a-zA-Z0-9]{5,}$/", $_POST['username'])) {
        $flag = 0;
        $username_err = "Username Must In Correct Format with no Spaces.";
    }

    //Password Validation
    if (empty($_POST['password'])) {
        $flag = 0;
        $password_err = "Password Field is Required ";
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $search_user = "select * from users where username='$username'";

    global $conn;

    $querry = mysqli_query($conn, $search_user);
    $count = mysqli_num_rows($querry);

    if ($count) {
        $user_pass = mysqli_fetch_assoc($querry); //Fetching that Row
        $db_pass = $user_pass['password']; //Accessing password from the assoc array

        //Auth
        $pass_decode = password_verify($password, $db_pass);
        
        if ($pass_decode) {
            //Now lets save his some important data for next pages in session
            $_SESSION['name'] = $user_pass['name'];
            $_SESSION['username'] = $user_pass['username'];

            $status = "<div class='alert alert-success' role='alert'>Authentication Done,..</div>";
            //Lets Redirect Him to IDE
            header('location:ide.php');
        } else {
            $err = 1;
            $status = "<div class='alert alert-danger' role='alert'>Incorret Credentials</div>";
        }
    } else {
        $err = 1;
        $status = "<div class='alert alert-danger' role='alert'>Something went wrong</div>";
    }
}



?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - CodeEater</title>
    <meta name="description" content="Sign Up Page for code eater">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Include bootstrap styles -->
    <link rel="stylesheet" href="./../public/bootstrap/css/bootstrap.min.css">

    <!-- Custom Css -->
    <link rel="stylesheet" href="../public/css/signup.css">

</head>

<body>
    <section>

        <div class="container-fluid">
            <div class="row " style="height:100%;">

                <div class="col-md-4 col-12 p-5">
                    <div class="logo">
                        <h3>CodeEater</h3>
                    </div>
                    <h1>Welcome Back,Login to continue </h1>

                    <!-- Sign Up Form -->
                    <form action="login.php" method="post" class="">


                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" name="username" placeholder="Enter Username" class="form-control py-2" id="username">
                            <div class="text-danger small">
                                <?php echo $username_err; ?>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" name="password" placeholder="Enter Password " class="form-control py-2" id="password">
                            <div class="text-danger small">
                                <?php echo $password_err; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100">Login</button>

                        <div class="w-100 my-2 text-center">
                            <span class="text-muted">Don't have account?</span>
                            <a href="signup.php" class="fw-bold text-decoration-none text-dark">Sign up</a>
                        </div>

                        <?php
                        if ($err) {
                            echo $status;
                        } else {
                            if ($status) {
                                echo $status;
                            }
                        }
                        ?>
                    </form>


                </div>

                <div class="col-md-8 d-md-block d-none" style="background-image:url('../public/images/signup/signup.jpg');background-size:cover;background-repeat:no-repeat;">
                </div>
            </div>
        </div>
    </section>
    <!-- Include bootstrap JS for plugins -->
    <script src="./../public/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
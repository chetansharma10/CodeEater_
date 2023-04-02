<?php
include '../database/connection.php';

$name_err = $username_err = $mail_err = $github_err = $password_err = $status = "";
$err = 0;

//Validation By Regex
function validateByRegex($regex, $data)
{
    if (preg_match($regex, $data)) {
        return true;
    }
    return false;
}

//Validation By Uniqness
function isUnique($field, $data)
{
    global $conn;
    $Uquerry = "select * from users where $field ='$data'";
    $query = mysqli_query($conn, $Uquerry);

    $urlCount = mysqli_num_rows($query);

    if ($urlCount > 0) {
        return false;
    }
    return true;
}

//For Github Link Validation Function
function validateUrl($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }
    return false;
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $flag = 1;
    //Name Validation
    if (empty($_POST['name'])) //Validation By Length
    {
        $flag = 0;
        $name_err = "** Name Field Required ";
    } else if (!validateByRegex("/^[a-zA-Z'. -]+$/", $_POST['name'])) //Validation By Format
    {
        $flag = 0;
        $name_err = "** Name Should be in Correct Format Like 'Shashwat Singh'.";
    }

    //URL Validation
    if (empty($_POST['githublink'])) //Validation By Length
    {
        $flag = 0;
        $github_err = "** Github Account Link Required ";
    } else if (!validateUrl($_POST['githublink'])) //Validation By Formate
    {
        $flag = 0;
        $github_err = "** Url Must be in Correct Formate Like 'https://...'.";
    } else if (!isunique("git_link", $_POST['githublink'])) //Validation is this account is unique or not
    {
        $flag = 0;
        $github_err = "** Someone Already Registered With This Account. ";
    }

    //Mail Validataion
    if (empty($_POST['email'])) //Validation By Length
    {
        $flag = 0;
        $mail_err = "** Email Required ";
    } else if (!validateByRegex("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email'])) //Validation By Regex
    {
        $flag = 0;
        $mail_err = "** Email Should Be In Correct Format.";
    } else if (!isUnique("email", $_POST['email'])) {
        $flag = 0;
        $mail_err = "** Already An Account Exist With This Mail.";
    }

    //Username Validation
    if (empty($_POST['username'])) {
        $flag = 0;
        $username_err = "** Username Field is Required ";
    } else if (!validateByRegex("/^[a-zA-Z0-9]{5,}$/", $_POST['username'])) {
        $flag = 0;
        $username_err = "** Username Must In Correct Format with no Spaces.";
    } else if (!isUnique("username", $_POST['username'])) {
        $flag = 0;
        $username_err = "** Someone Already Taken this Username ";
    }

    //Password Validation
    if (empty($_POST['password'])) {
        $flag = 0;
        $password_err = "** Password Field is Required ";
    } else if (strlen($_POST['password'] < 8)) {
        $flag = 0;
        $password_err = "** Password Length Should Be Greater Then 7.‼";
    }

    if ($flag == 1) {


        //To Prevent From SQL Injection
        global $conn;

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $gitlink = mysqli_real_escape_string($conn, $_POST['githublink']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        //Encrypting Password
        $password = password_hash($password, PASSWORD_BCRYPT);

        //Insert querry
        $insertquery = "insert into users( name, email, git_link, username, password) values('$name','$email','$gitlink','$username','$password')";

        $iquerry = mysqli_query($conn, $insertquery);

        if ($iquerry) {
            $status = "<div class='alert alert-success' role='alert'>
                        Account Created Successfully. You are going to redirect to login page within
                        <strong class='timer'>5</strong> sec
                        <script type='text/javascript'>
                            const timer = document.querySelector('.timer');

                            let timerValue = 5;
                            timer.innerText = timerValue;

                            const timer$ = setInterval(()=>{
                                if(timerValue===0){
                                    clearInterval(timer$);
                                   window.location.href= window.location.href.replace('signup','login')
                                }
                                timerValue = timerValue - 1
                                timer.innerText = timerValue;
                            },1000)
                        </script>
                    </div>";
        } else {
            $status = "<div class='alert alert-danger' role='alert'>Something went wrong</div>";
            $err = 1;
        }
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign Up - CodeEater</title>
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
                    <h1>Create an account <br class="d-none d-md-block">for free..</h1>

                    <!-- Sign Up Form -->
                    <form action="signup.php" method="post" class="">

                        <div class="mb-3">
                            <label for="fullname" class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" placeholder="Enter Full Name" class="form-control py-2" id="fullname">
                            <div class="text-danger small">
                                <?php echo $name_err; ?>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="githubLink" class="form-label fw-bold">Github Url</label>
                            <input type="text" name="githublink" placeholder="Enter Github Link" class="form-control py-2" id="githubLink">
                            <div class="text-danger small">
                                <?php echo $name_err; ?>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" placeholder="Enter Email Address" class="form-control py-2" id="email">
                            <div class="text-danger small">
                                <?php echo $mail_err; ?>
                            </div>
                        </div>



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

                        <button type="submit" class="btn btn-dark btn-lg w-100">Create an account</button>

                        <div class="w-100 my-2 text-center">
                            <span class="text-muted">Already having account?</span>
                            <a href="login.php" class="fw-bold text-decoration-none text-dark">Login</a>
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
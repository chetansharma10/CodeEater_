<?php
session_start();
?>

<!DOCTYPE html>   
<html>   
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> Login Page </title>  

<?php include '../links/login_links.php' ?>

</head>    
<body>  

<?php
   include '../database/connection.php';
  //initializing Variables
  $username_err=$status=$password_err="";
  $err=0;

  //Validation by Regex
   function validateByRegex($regex,$data)
   {
      if (preg_match($regex, $data)) {
         return true;  
   }
   return false;
   }

  if($_SERVER['REQUEST_METHOD']=="POST")
  {
     $flag=1;
    //Username Validation
    if(empty($_POST['username']))
    {
      $flag=0;
      $username_err = "Username Needed For LogIn 🤦‍♂️";
    }
    else if(!validateByRegex("/^[a-zA-Z0-9]{5,}$/",$_POST['username']))
    {
      $flag=0;
       $username_err = "Username Must In Correct Format with no Spaces.😡";
    }
    
    //Password Validation
    if(empty($_POST['password']))
    {
      $flag=0;
      $password_err="Password Field is Required 🤦‍♂️";
    }

     $username=$_POST['username'];
     $password = $_POST['password'];
     
     $search_user = "select * from users where username='$username'";

     global $conn;

     $querry = mysqli_query($conn,$search_user);
     $count = mysqli_num_rows($querry);
     
     if($count)
     {
           $user_pass = mysqli_fetch_assoc($querry); //Fetching that Row
           $db_pass = $user_pass['password']; //Accessing password from the assoc array

           //Auth
           $pass_decode = password_verify($password,$db_pass);

           if($pass_decode)
           {
              //Now lets save his some important data for next pages in session
              $_SESSION['name']=$user_pass['name'];
              
               $status="Authenticated Successfully 🎉";
               //Lets Redirect Him to IDE
               header('location:ide.php');
           }
           else
           {
              $err=1;
              $status="Incorrect Credentials 😡";
           }

     }
     else
     {
        $err=1;
        $status="User Not Found 😒 | Invalid Username";
     }
  }
 

 

?>

<div class="container" style="position:absolute; right:450px">
   <div class="row" style="margin-top:45px">
      <div class="col-md-4 col-md-offset-4">
           <h4> Login 🔑 | Auth</h4><hr>

           <?php 
           if($err==0) 
           { 
             echo "<div class='alert alert-sucess'>";
             echo $status; 
             echo "</div>";
           }
           else
           {
              echo "<div class='alert alert-danger'>";
              echo $status;
             echo "</div>";

           }
           ?>

           <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
 
   
    
    <div class="form-group">
                 <label>Username</label>
                 <input type="text" class="form-control" name="username" placeholder="Enter Username">
                 <span class="text-danger"><?php echo $username_err; ?></span>
               </div>

              <div class="form-group">
                 <label>Password</label>
                 <input type="password" class="form-control" name="password" placeholder="Enter password">
                 <span class="text-danger"><?php echo $password_err; ?></span>
               </div>

              <button type="submit" class="btn btn-block btn-primary">Sign In</button>
              <br>

      <h4>New User?</h4>
      <a href="signup.php">Create New Account!</a>   
</div>   

</body>     
</html>  
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php include '../links/login_links.php' ?>

</head>
<body>

<?php

include '../database/connection.php';

//initializing Variables

$name_err=$username_err=$mail_err=$github_err=$password_err=$status="";
$err=0;

//Validation By Regex
function validateByRegex($regex,$data)
{
   if (preg_match($regex, $data)) {
      return true;  
  }
  return false;
}

//Validation By Uniqness
function isUnique($field,$data)
{
   global $conn;
   $Uquerry = "select * from users where $field ='$data'";
   $query = mysqli_query($conn,$Uquerry);

   $urlCount = mysqli_num_rows($query);

   if($urlCount>0)
   {
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



if($_SERVER['REQUEST_METHOD']=="POST")
{
    $flag = 1;
    
   //Name Validation
    if(empty($_POST['name']))  //Validation By Length
     {
      $flag=0;
       $name_err="Name Field Required 🤦‍♂️";
    }
    else if(!validateByRegex("/^[a-zA-Z'. -]+$/",$_POST['name']))  //Validation By Format
    {
      $flag=0;
       $name_err="Name Should be in Correct Format Like 'Shashwat Singh'. 😡";
    }

    //URL Validation
    if(empty($_POST['githublink'])) //Validation By Length
    {
      $flag=0;
       $github_err= "Github Account Link Required 🤦‍♂️";
    }
    else  if(!validateUrl($_POST['githublink'])) //Validation By Formate
    {
      $flag=0;
      $github_err="Url Must be in Correct Formate Like 'https://...'. 😡";
    }
    else if(!isunique("git_link",$_POST['githublink'])) //Validation is this account is unique or not
    {
      $flag=0;
      $github_err="Someone Already Registered With This Account. 😒";
    }

    //Mail Validataion
    if(empty($_POST['email'])) //Validation By Length
    {
      $flag=0;
      $mail_err= "Mail Required 🤦‍♂️";
    }
    else if(!validateByRegex("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",$_POST['email']))//Validation By Regex
    {
      $flag=0;
       $mail_err = "Mail Should Be In Correct Format.😡";
    }
    else if(!isUnique("email",$_POST['email']))
    {
      $flag=0;
       $mail_err= "Already An Account Exist With This Mail.😒";
    }
    
    //Username Validation
    if(empty($_POST['username']))
    {
      $flag=0;
      $username_err = "Username Field is Required 🤦‍♂️";
    }
    else if(!validateByRegex("/^[a-zA-Z0-9]{5,}$/",$_POST['username']))
    {
      $flag=0;
       $username_err = "Username Must In Correct Format with no Spaces.😡";
    }
    else if(!isUnique("username",$_POST['username']))
    {
      $flag=0;
      $username_err = "Someone Already Taken this Username 😏";
    }

    //Password Validation
    if(empty($_POST['password']))
    {
      $flag=0;
      $password_err="Password Field is Required 🤦‍♂️";
    }
   else  if(strlen($_POST['password']<8))
    {
      $flag=0;
       $password_err = "Password Length Should Be Greater Then 7.‼";
    }
    
    if($flag==1)
    { 

    
    //To Prevent From SQL Injection
    global $conn;

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $gitlink = mysqli_real_escape_string($conn,$_POST['githublink']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    //Encrypting Password
    $password = password_hash($password,PASSWORD_BCRYPT);
    
    //Insert querry

    $insertquery = "insert into users( name, email, git_link, username, password) values('$name','$email','$gitlink','$username','$password')";
    
    $iquerry = mysqli_query($conn,$insertquery);

    if($iquerry)
    {
      $status="Account Created Successfully 🎉, Login and use IDE";
    }
    else
    {
      $status = "Some Error Caused 🤦‍♂️, Try Again";
      $err=1;
    }

    }
    

}

?>


<div class="container" style="position:relative; right: 400px;">
   <div class="row" style="margin-top:45px">
      <div class="col-md-4 col-md-offset-4">
           <h4>Create New Account 👩‍💻 |  Register </h4><hr>
           
           <?php 
           if($err==0) 
           { echo "<div class='alert alert-sucess'>";
            echo $status; 
           echo "</div>";
           }
           else
           {
              echo "
              <div class='alert alert-danger'>
              .$status.
</div>    
              ";

           }
           ?>



           <form action="" method="post">
     
           <div class="form-group">
                 <label>Name</label>
                 <input type="text" class="form-control" name="name" placeholder="Enter full name">
                 <span class="text-danger"><?php echo $name_err; ?></span>
               </div>

              <div class="form-group">
                 <label>GitHub Link</label>
                 <input type="text" class="form-control" name="githublink" placeholder="Enter Github Link">
                 <span class="text-danger"><?php echo $github_err; ?></span>
               </div>

     
              <div class="form-group">
                 <label>Mail</label>
                 <input type="mail" class="form-control" name="email" placeholder="Enter Email">
                 <span class="text-danger"><?php echo $mail_err; ?></span>
               </div>

    <div class="form-group">
                 <label>Username</label>
                 <input type="text" class="form-control" name="username" placeholder="Enter the Username">
                 <span class="text-danger"><?php echo $username_err; ?></span>
               </div>

    <div class="form-group">
                 <label>Password</label>
                 <input type="password" class="form-control" name="password" placeholder="Set the Password">
                 <span class="text-danger"><?php echo $password_err; ?></span>
               </div>

              <button type="submit" class="btn btn-block btn-primary">Sign Up</button>
              <br>
              <a href="login.php">I already have an account, sign in</a>
    </div>
              

  </from>
 </div>
</div>


</body>
</html>


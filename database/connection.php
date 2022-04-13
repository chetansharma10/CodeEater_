<?php

   $username = "root";
   $password = "TOb1SqpLSjPGLVYAwU6G";
   $server = "codeeater.cfq636670aed.ap-south-1.rds.amazonaws.com";
   $dbname = "codeEater";

  $conn = mysqli_connect($server,$username,$password);

   $db =  mysqli_select_db($conn,$dbname);

   if($db)
   {
      echo "<script> console.log('Connected With Database Scuccessfully 🎉') </script>";
   }
   else
   {
      
       die("Not Abe to Connect ".mysqli_connect_error());
   }

   //uname --> root
   //aws my sql --> TOb1SqpLSjPGLVYAwU6G
?>
<?php

   $username = "root";
   $password = "";
   $server = "localhost";
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

?>
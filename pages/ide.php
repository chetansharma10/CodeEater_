<?php
  session_start();

  if(!isset($_SESSION['name']))
  {
      //If Some Directly Hit this url then not able to access this
      header('location:login.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>💻CodeEATER</title>

    <link rel="stylesheet" href="../css/style.css" />
    
</head>
<body>

    <div class="header">
        <h4 style="position:relative; top:20px; left: 20px"> Hello 👋 <?php echo $_SESSION['name'] ?> </h4>
     
        <div style=" position:relative; bottom:15px;right:60px">
        <a href="logout.php"> 
            <button class="btn" style="height:40px;background-color:white;border:solid white">
            <input type="image" name="search" class="" src="data:image/png;base64,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" height="45px"> 
        
</div> 
    </a>
     
    <center><img  class="pic" src="../images/logo.png" alt="missing"></center>
    <hr>
        
        <div class="control-panel">
            Select Language:
            &nbsp; &nbsp;
            <select id="ext" class="languages" onchange="changeLanguage()">
                <option value="c"> C </option>
                <option value="cpp"> C++ </option>
                <option value="py"> Python </option>
            </select>
	    
            <button class="clear" > clear </button>
            <button class="btn" onclick="executeCode()"> &#9654  </button>    
           </div>
    

      </div>  
   <input type="hidden" id='username' value=<?php echo $_SESSION['username'] ?>>
    <div class="outer"> 

        
            <div class="editor" id="editor"></div>
        

            <div id="input" > 
                <div> 
                    
                     <div class="in"> 
                        <textarea  id="inFor" placeholder="input is here.."></textarea>    
                
                    </div>
  
                </div>
                
            </div>
                <div id="overall" style=" background-color:rgb(255, 255, 255)"> 
                    <div id="output" style="background-color: rgb(251, 251, 251); color: rgb(0, 0, 0); border: 2px solid black;"> <center> Status </center> 
                    <center> <h5 style="margin-top: 2%; color: red;" id="status">message</h5></center>
                    </div> 

                <div class="message" id=" message" style="background-color: black; color: aliceblue; "> <center> Output</center>
                 <center> <h5 style="margin-top: 2%; color: green;" id="out"> output</h5></center>    
                </div>
                   <div id="compilation" style="background-color: rgb(254, 254, 254); color: rgb(0, 0, 0);border: 2px solid white;"><center> Compilation Time</center>
                    <center> <h5 id="ctime" style="margin-top: 2%; color: red;"> ctime</h5></center>     </div> 
           
                </div>
        
    </div>    


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/lib/ace.js"></script>
    <script src="../js/lib/theme-monokai.js"></script>
    <script src="../js/index.js"></script>

</body>
</html>

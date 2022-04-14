<?php
    
    function runCmd($compile,$execute)
    {  
      $ret=null;
      $c_time=null;

      exec($compile." 2>&1", $c_time, $ret);
      

      if($ret!=0)
      {
          $res=array(
            'message' => "Some Error Caused",
            'output' => $c_time,
            'succ' => 0
          );
          echo json_encode($res);
      }
      else
      {
	      $output=null;
	 
        exec($execute,$output,$ret);

          if($ret==0)
          {
            $res=array(
                  'message'=> "Successfully Compiled",
                  'ctime'=>$c_time,
		  'output'=>$output,
		  'succ' => 1
            );
            echo json_encode($res);

          }
          else
          {
            $res=array(
              'message' => "Some Error Caused",
              'output' => $output,
              'succ' => 0
            );
            echo json_encode($res);
          }
        
      }
    }

    if($_POST)
    {
	    //First Checking  that Dir is exist or not
	   if(!file_exists($_POST['uname']))
	   {
		   //If not then we will create it.
		   if(mkdir($_POST['uname']))
		   {
			  // echo "<script> console.log(Successfully Created) </script>";
		   }
		   else
		   {
			  // echo "Some Error Caused";
		   }
	   }
        
	   //Now creating File And puting it into specific folder
	$time=new DateTime();

        $codefilename=$time->format('d-m-Y-H-i-s').".".$_POST["ext"];
        $file=fopen($_POST['uname']."/".$codefilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST["code"]);
        fclose($file);

        $infilename=$time->format('d-m-Y-H-i-s');
        $file=fopen($_POST['uname']."/".$infilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST["in"]);
	fclose($file);


      
       switch($_POST['ext'])
       {
          case 'c':
            $compile='time g++ -o ./'.$_POST['uname'].'/'.str_replace('.c','.exe',$codefilename).' ./'.$_POST['uname'].'/'.$codefilename;
            $execute='cat ./'.$_POST['uname'].'codeFiles/'.$infilename.' | ./'.$_POST['uname'].'/'.str_replace('.c','.exe',$codefilename);
            runCmd($compile,$execute);
            break;
          
          case 'cpp':
		  $compile='time g++ -o ./'.$_POST['uname'].'/'.str_replace('.cpp','.exe',$codefilename).' ./'.$_POST['uname'].'/'.$codefilename;
		
            $execute='cat ./'.$_POST['uname'].'/'.$infilename.' | ./'.$_POST['uname'].'/'.str_replace('.cpp','.exe',$codefilename);
            runCmd($compile,$execute);
            break;

          case 'py':
            //$compile='time python3 ./codeFiles/'+$codefilename;
            $execute='time cat ./'.$_POST['uname'].'/'.$infilename.' | python3 ./'.$_POST['uname'].'/'.$codefilename;
	    //runCmd($compile,$execute);
	   
	    $output=null;
	    $ret=null;

	    exec($execute,$output,$ret);

          if($ret==0)
          {
            $res=array(
                  'message'=> "Successfully Compiled",
                  'ret' => $ret,
		  'output'=>$output,
		  'succ'=> 1
            );
            echo json_encode($res);

          }
          else
	  {
		  exec($execute.' 2>&1',$output,$ret);	  
            $res=array(
              'message' => "Some Error Caused ",
              'output' => $output,
	      'succ' => 0
	    );
	  
	    echo json_encode($res);
            }
            break;

          default:
            $res=array(
              'message' => "Some Error Caused 🤦",
              'output' => "This Language Not Supported by the System!",
              'succ' => 0
            );
            echo json_decode($res);
       
       }
        
    }
    else
    {
        echo "Bad Request!";
    }
?>

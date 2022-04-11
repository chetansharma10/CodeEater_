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

	$time=new DateTime();

        $codefilename=$time->format('d-m-Y-H-i-s').".".$_POST["ext"];
        $file=fopen("codeFiles/".$codefilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST["code"]);
        fclose($file);

        $infilename=$time->format('d-m-Y-H-i-s');
        $file=fopen("codeFiles/".$infilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST["in"]);
	fclose($file);


      
       switch($_POST['ext'])
       {
          case 'c':
            $compile='time g++ -o ./codeFiles/'.str_replace('.c','.exe',$codefilename).' ./codeFiles/'.$codefilename;
            $execute='cat ./codeFiles/'.$infilename.' | ./codeFiles/'.str_replace('.c','.exe',$codefilename);
            runCmd($compile,$execute);
            break;
          
          case 'cpp':
		  $compile='time g++ -o ./codeFiles/'.str_replace('.cpp','.exe',$codefilename).' ./codeFiles/'.$codefilename;
		
            $execute='cat ./codeFiles/'.$infilename.' | ./codeFiles/'.str_replace('.cpp','.exe',$codefilename);
            runCmd($compile,$execute);
            break;

          case 'py':
            //$compile='time python3 ./codeFiles/'+$codefilename;
            $execute='time cat ./codeFiles/'.$infilename.' | python3 ./codeFiles/'.$codefilename;
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

<?php
    
    function runCmd($compile,$execute)
    {  
      $ret=null;
      $c_time=null;
      exec($compile." 2>&1", $c_time, $ret); 
      //By this way we are fetching error or time
      
      //if there is some error in code then $ret ==  become 0 which means this cmd have
      // Some Error So we will not move forward
      if($ret==0)
      {
          $res=array(
            'message' => "Some Error Caused 🤦‍♂️",
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
                  'message'=> "Successfully Compiled 🎉",
                  'ctime'=>$c_time,
                  'output'=>$output,
                  'succ' => 1
            );
            echo json_encode($res);

          }
          else
          {
            $res=array(
              'message' => "Some Error Caused 🤦‍♂️",
              'output' => $output,
              'succ' => 0
            );
            echo json_encode($res);
          }
        
      }
    }

    if($_POST)
    {
      
        $time=new DateTime(); //First We will fetch the current at which time client come
        
        //then create the code File
        $codefilename=$time->format('d-m-Y-H-i-s').".".$_POST["ext"];
        $file=fopen("codeFiles/".$codefilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST["code"]);
        fclose($file);

        //then Create the input file
        $infilename=$time->format('d-m-Y-H-i-s');
        $file=fopen("codeFiles/".$infilename,"w") or die("Unable to Open the File");
        fwrite($file,$_POST['in']);
        fclose($file);
      
       switch($_POST['ext'])
       {
          case 'c':
            $compile='time g++ -o ./codeFiles/'.str_replace('.c','.exe',$codefilename).' ./codeFiles/'.$codefilename;
            $execute='cat '.$infilename.' | ./codeFiles/'.str_replace('.c','.exe',$codefilename);
            runCmd($compile,$execute);
            break;
          
          case 'cpp':
            $compile='g++ -o ./codeFiles/'.str_replace('.cpp','.exe',$codefilename).' ./codeFiles/'.$codefilename.' ?> `tty`';
            $execute='cat '.$infilename.' | ./codeFiles/'.str_replace('.cpp','.exe',$codefilename);
            runCmd($compile,$execute);
            break;

          case 'py':
            $execute='/usr/bin/time -o ./codeFiles/'.$infilename.'_time cat ./codeFiles/'.$infilename.' | python3 ./codeFiles/'.$codefilename.' 2> ./codeFiles/'.$infilename.'_err';
            $c_time='cat ./codeFiles/'.$infilename.'_time';
            $err='cat ./codeFiles/'.$infilename.'_err';

            $output=null;
            $ret=null;
            $tim=null;
            $errr=null; 

            exec($execute,$output,$ret);
            exec($c_time,$tim);
            
          if($ret==0)
          {
            $res=array(
                  'message'=> "Successfully Compiled",
                  'ctime' => $c_time,
                  'output'=>$output,
                  'succ'=> 1
            );
            echo json_encode($res);
          }
          else
          {
            exec($err,$errr);
            $res=array(
              'message' => "Some Error Caused 🤦",
              'output' => $errr,
              'succ' => 0
            );

            echo json_encode($res);
            }

            break;

          default:
            $res=array(
              'message' => "Some Error Caused 🤦‍♂️",
              'output' => "This Language Not Supported by the System!",
              'succ' => 0
            );
           echo json_encode($res);
       
       }
        
    }


    //For Random Request
    else
    {
        echo "Bad Request!";
    }
?>
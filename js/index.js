let editor;

window.onload = function() {
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/c_cpp");
}

function changeLanguage() {
    var language = document.getElementById('ext').value;
    
    if(language == 'c' || language == 'cpp') editor.session.setMode("ace/mode/c_cpp");

    else if(language == 'py') editor.session.setMode("ace/mode/python");
}

const addNewLineForHTML=(datas)=>{
      let correctFormat = "";
        
      if(datas.includes('\n'))
      {
          datas=datas.split('\n');
      }

      for(data of datas)
      {
         correctFormat+=data+"<br>";
      }

      return correctFormat;
}

function executeCode()
{
   
    

    //Creating AJAX call
     
        var data= new FormData;
        data.append("ext",document.getElementById('ext').value);
        data.append("code",editor.getSession().getValue());
        data.append("uname",document.getElementById('username').value);
	const input = document.getElementById('inFor').value;
	//console.log(input);
	data.append("in",input);
          console.log(data);
        
        let url = "/codeFront/util/compile.php";
    

        //Fetch

          fetch(url,
                {
                    method : "post",
                    body : data
                }).then((res)=>{
                    res.json().then((res)=>{
			    console.log(res);

			   if(res['succ']==1)
			    {
                              document.getElementById('status').innerHTML=res['message']+' 🎉';
			    }
			    else 
			    {	    
                              document.getElementById('status').innerHTML=res['message']+' 🤦';
			    }
			    document.getElementById('out').innerHTML=addNewLineForHTML(res['output']);
			    document.getElementById('ctime').innerHTML=addNewLineForHTML(res['ctime']);
                    });
                }).catch((e)=>{
                    console.log(e);
                })

        return false;

}

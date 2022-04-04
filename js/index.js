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

function executeCode()
{
    alert($("#ext").val());
    

        var data= new FormData;
        
        data.append("ext",document.getElementById('ext').value);
        data.append("code",editor.getSession().getValue());
        
        const input = document.getElementById("customIn").value;
        console.log(input);
        
        data.append("in",input);

        let url = "/codeFront/util/compile.php";
    
          fetch(url,
                {
                    method : "post",
                    body : data
                }).then((res)=>{
                    res.json().then((res)=>{
                        document.getElementById('output').innerHTML=(res['message']);
                    });
                }).catch((e)=>{
                    console.log(e);
                })

        return false;

}
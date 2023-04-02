let editor;


async function callAPI(input) {

    /** Preparing Form Data */
    var data = new FormData;

    /** Appending in Form Data */
    data.append("ext", document.getElementById('ext').value);
    data.append("code", editor.getSession().getValue());
    data.append("uname", document.getElementById('username').value);
    data.append("in", input);

    /** API call Post */
    return await fetch("http://localhost/codeEater_/util/compile.php", { method: "post", body: data });

}

const setupTerminal = () => {
    $('.terminal-container').terminal({

        run: function (...input) {
            const options = $.terminal.parse_options(input)._;

            let finalInput = '';

            if (options.length > 0) {
                finalInput = options?.reduce((a, b) => a + ' ' + b);
            }

            (async () => {
                let response = await callAPI(finalInput);
                let data = await response.json()
                console.log(data);
                if (!data.succ) {
                    let warning = data.output.reduce((a, b) => a + ' ' + b);
                    this.error(data.message);
                    this.error(warning);
                }

                if (data.succ) {
                    let outputSucc = data.output.reduce((a, b) => a + ' ' + b);
                    this.echo(data.message);
                    this.echo('Ouput ' + outputSucc);
                    if(data.ctime){
                        let ouputTimeSucc = data.ctime.reduce((a, b) => a + ' ' + b);
                        this.echo('Time ' + ouputTimeSucc);
                    }

                }
            })().catch(err => {
            });

        },
        help: function () {
            this.echo("run [input] to execute command");
        }
    }, {
        checkArity: false,
        prompt: `${user}-codeeater $ `,
        greetings: `Welcome to Code Eater,\nType 'help' to see available commands`
    });

}



window.onload = function () {

    editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/c_cpp");

    editor.session.selection.on('changeCursor', function (e) {
        let iRowPosition = editor.selection.getCursor().row; // to get the Row Position 
        let iColumnPosition = editor.selection.getCursor().column; // to get the Column Position 
        document.getElementById('line-col').innerText = `Ln ${iRowPosition},Col ${iColumnPosition}`;
    });

    setupTerminal();

}

function changeLanguage() {
    var language = document.getElementById('ext').value;

    if (language == 'c' || language == 'cpp') editor.session.setMode("ace/mode/c_cpp");

    else if (language == 'py') editor.session.setMode("ace/mode/python");
}

const addNewLineForHTML = (datas) => {
    let correctFormat = "";

    if (datas.includes('\n')) {
        datas = datas.split('\n');
    }

    for (data of datas) {
        correctFormat += data + "<br>";
    }

    return correctFormat;
}



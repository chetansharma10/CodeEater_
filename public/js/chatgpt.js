/** Binded Variable Used for activate or inactive the dialog on screen */
let chatgptRef = document.getElementById('chatgpt-dialog');

/** Binded Variable For Toggle the switch active/inactive */
let chatgptSwitch = document.getElementById('chatGptSwitch');

/** Binded Variable For Close the chatgpt dialog */
let chatgptCloseBtn = document.getElementById('close-gpt-btn');

/** Binded Variable For main conversation in the chat dialog box */
let conversation = document.getElementById('conversation');

/** Binded Variable For send the input and execute main api call */
let userSendBtn = document.getElementById('send');

/** Binded Variable For input something to api */
let userInput = document.getElementById('user-input');

/** Your API KEY */
let API_KEY = 'api-key';

/** Attach Listener when switch changes */
chatgptSwitch.addEventListener('change', (e) => {

    /** Taking checked value whether true/false  */
    let openDialog = e.target.checked;

    /** If value is true */
    if (openDialog) {

        /** Add active class ie. display:block; */
        chatgptRef.classList.add('active');
    }
    else {

        /** Remove active class ie. by default display:none; */
        chatgptRef.classList.remove('active');
    }
});


/** When user click on close dialog button */
chatgptCloseBtn.addEventListener('click', () => {

    /** Remove active class ie. by default display:none; */
    chatgptRef.classList.remove('active');

    /** Setting switch value back to false  */
    chatgptSwitch.checked = false;

});

/** Get Current Time */
function getCurrentTime() {

    let date = new Date();

    return ((date.getHours().toString()).length > 1 ? date.getHours() : "0" + date.getHours()) + ":" + ((date.getMinutes().toString()).length > 1 ? date.getMinutes() : "0" + date.getMinutes());
}

/** Create and add user View inside conversation chat */
function addUserSideView() {

    let createElementUser = document.createElement('span');

    createElementUser.innerHTML = `
        <div class="send px-3 py-3 d-flex alig-items-center animate__animated animate__fadeIn animate__faster justify-content-end ">
            <p class=" d-flex align-items-start flex-column text-break bg-success text-white rounded px-1 " style="width:max-content;height:max-content;">
                ${userInput.value}
                <small style="font-size:0.5rem;" class="align-self-end">${getCurrentTime()}</small>
            </p>
        </div>`;

    conversation.appendChild(createElementUser);

    userInput.value = '';

    conversation.scrollTop = conversation.scrollHeight;

}

/** Create GPT Side View */
function addGPTSideView(json) {

    let createElementGPT = document.createElement('span');

    createElementGPT.innerHTML = `
    <div class="send px-3 py-3 d-flex alig-items-center justify-content-start">
        <p class="d-flex gpt align-items-start flex-column text-break rounded px-1 " style="width:max-content;height:max-content;">
            ${json.choices[0].text}
            <small style="font-size:0.5rem;" class="align-self-end">${getCurrentTime()}</small>
        </p>
    </div>`;

    conversation.appendChild(createElementGPT);

    conversation.scrollTop = conversation.scrollHeight;


}

/** Create animation View While loading of api response */
function addAnimationView() {

    let animation = document.createElement('div');

    animation.id = 'gpt-animation';

    animation.className = 'w-100 d-flex alig-items-center mx-2 justify-content-start';

    animation.innerHTML = `
        <div class="snippet ms-4" data-title="dot-flashing">
        <div class="stage">
        <div class="dot-flashing"></div>
        </div>
    </div>`;

    conversation.appendChild(animation);
}


function executeNetworkRequest() {
    /** Add User Side View */
    addUserSideView();

    /** Add Loader or Animation */
    addAnimationView();

    fetch(
        `https://api.openai.com/v1/completions`,
        {
            body: JSON.stringify(
                {
                    "model": "text-davinci-003",
                    "prompt": userInput.value,
                    "max_tokens": 1000,
                    "temperature": 0
                }
            ),
            method: "POST",
            headers: {
                "content-type": "application/json",
                Authorization: `Bearer ${API_KEY}`,
            },
        }
    ).then((response) => {

        if (response.ok) {

            response.json().then((json) => {

                /** Remove Animation */
                conversation.removeChild(document.getElementById('gpt-animation'));

                /** Add GPT Side View */
                addGPTSideView(json);

                console.log(json)

            });
        }
    }).catch((error) => {

        /** Remove Animation */
        conversation.removeChild(document.getElementById('gpt-animation'));

    });
}




/** When user click on send btn inside chatgpt dialog */
userSendBtn.addEventListener('click', () => {

    /** If there is no value do nothing */
    if (!userInput.value) {
        return;
    }

    /** Run api call and do bindings and updates */
    executeNetworkRequest();

});



/** When user click on send btn inside chatgpt dialog */
userInput.addEventListener('keydown', (e) => {

    /** If user hits enter */
    if(e.keyCode === 13){
        /** If there is no value do nothing */
        if (!userInput.value) {
            return;
        }

        /** Run api call and do bindings and updates */
        executeNetworkRequest();
    }
 

});

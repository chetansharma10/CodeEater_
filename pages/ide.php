<?php
session_start();

if (!isset($_SESSION['name'])) {
    //If Some Directly Hit this url then not able to access this
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IDE - CodeEater</title>
    <meta name="description" content="Sign Up Page for code eater">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Terminal Website -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.terminal/css/jquery.terminal.min.css" />



    <!-- Include bootstrap styles -->
    <link rel="stylesheet" href="./../public/bootstrap/css/bootstrap.min.css">

    <!-- Custom Css -->
    <link rel="stylesheet" href="../public/css/ide.css">
    <link rel="stylesheet" href="../public/css/style.css" />




</head>

<body>

    <script type="text/javascript">
        var user = "<?php echo $_SESSION['username'] ?>"
    </script>
    <section>
        <div class="container-fluid bg-custom-dark position-relative" style="height:100vh;">
            <div class="row position-relative w-100 h-100">
                <div class="col-lg-2 d-none d-lg-block  text-white border-end border-dark bg-light-dark shadow shadow-lg p-0 m-0 ">
                    <explorer class="d-block">
                        <p class="text-uppercase fw-bold  p-2" style="border-bottom:1px solid #d3d3d345">Explorer</p>
                        <div class="w-100 px-2">
                            <ul class="list-unstyled ">
                                <li>
                                    <a href="ide.php" class="text-decoration-none text-white">
                                        <span class="d-flex align-items-start flex-column ">
                                            <span class="text-uppercase">Code Eater</span>
                                            <small class="text-muted fs-15"> <?php echo date("F j, Y, g:i a"); ?></small>
                                        </span>
                                    </a>
                                </li>

                                <li class="mt-3">
                                    <span class="text-decoration-none text-white  ">
                                        <span class="form-check ms-0 ps-0 d-flex justify-content-between  form-switch">
                                            <div class="d-flex align-items-center gap-2 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                                </svg>
                                                <span class=" d-none d-lg-flex mx-2 align-items-start flex-column small">
                                                    <p class="p-0 m-0">Chat Bot</p>
                                                    <small class="text-muted">Support</small>
                                                </span>
                                            </div>
                                            <input class="form-check-input" id="chatGptSwitch" type="checkbox">
                                        </span>
                                    </span>
                                </li>
                            </ul>
                        </div>

                    </explorer>
                </div>
                <div class="col-lg-10 col-12  text-white border-end border-dark bg-light-dark  ">
                    <outlet-window>

                        <div class="position-relative  w-100" id="editor">
                        </div>

                        <div class=" position-fixed d-flex flex-column justify-content-start w-100 bottom-0" style="background-color:black;">

                            <div class="row p-0 m-0 ">
                                <div class="col-12 ">
                                    <h1 class="px-2 my-2 logo">CodeEater</h1>
                                </div>

                                <div class="col-12" style="overflow:scroll;height:200px;max-height:175px;">
                                    <div class="terminal-container ">
                                    </div>
                                </div>
                            </div>






                        </div>

                    </outlet-window>
                </div>

                <chat-gpt-dialog class="animate__slideInDown my-5 shadow-lg animate__animated  chatgpt-dialog shadow-lg bg-dark" id="chatgpt-dialog">

                    <div class="d-flex align-items-center text-white justify-content-between chatgpt-header p-2">
                        <div class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                            </svg>
                            <span class="fw-bold small">Help & Support</span>
                        </div>
                        <button id="close-gpt-btn" class="btn btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </button>
                    </div>

                    <div class="chat-details bg-dark  position-relative h-100">

                        <div id="conversation" class="w-100 h-100 mb-5 bg-dark p-3 overflow-scroll">

                        </div>


                    </div>
                    <div class="inputs p-3 bg-dark w-100  " style="border-bottom-left-radius:0.5rem;border-bottom-right-radius:0.5rem;">
                        <div id="chat-input" class="w-100 rounded  d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1 position-relative d-flex align-items-center ">
                                <input type="text" id="user-input" placeholder="Write something for help" class=" form-control " id="exampleInputEmail1" aria-describedby="emailHelp">
                                <button id="send" class="btn btn-primary btn-sm rounded-5 position-absolute end-0 me-2 top-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                                    </svg>
                                </button>

                            </div>

                        </div>
                    </div>


                </chat-gpt-dialog>
            </div>

            <status-bar class="d-block bg-dark w-100 text-white position-absolute py-1 bottom-0 start-0">
                <div class="row">

                    <div class="col-12 d-flex align-items-center justify-content-end rightStatus">

                        <a href="logout.php" class="mx-2 status text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-escape" viewBox="0 0 16 16">
                                <path d="M8.538 1.02a.5.5 0 1 0-.076.998 6 6 0 1 1-6.445 6.444.5.5 0 0 0-.997.076A7 7 0 1 0 8.538 1.02Z" />
                                <path d="M7.096 7.828a.5.5 0 0 0 .707-.707L2.707 2.025h2.768a.5.5 0 1 0 0-1H1.5a.5.5 0 0 0-.5.5V5.5a.5.5 0 0 0 1 0V2.732l5.096 5.096Z" />
                            </svg>
                        </a>


                        <a href="ide.php" class="mx-2 status text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                            </svg>
                        </a>
                        <span id="line-col" class=" mx-2 status">Ln 20,Col 22</span>


                        <select id="ext" class="languages text-white bg-dark" onchange="changeLanguage()">
                            <option value="c"> C </option>
                            <option value="cpp"> C++ </option>
                            <option value="py"> Python </option>
                        </select>

                        <span class=" mx-2 status ">🖐 Hello <span class="" id="username"><?php echo $_SESSION['username'] ?></span></span>
                    </div>
                </div>
            </status-bar>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../public/js/lib/ace.js"></script>
    <script src="../public/js/lib/theme-monokai.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.terminal/js/jquery.terminal.min.js"></script>


    <script src="./../public/js/chatgpt.js"></script>
    <script src="../public/js/index.js"></script>




</body>
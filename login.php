<<<<<<< HEAD
    ```html
    <!DOCTYPE html>
    <html lang="it">
=======
<?php
session_start();
require_once 'lib/conn.php';
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
>>>>>>> 952475e5957c3f19ca9976061a255bdeb93ae5f8

    <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Login - Monitoraggio Ambientale</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    </head>

    <body class="bg-gradient-primary">

    <div class="container">

    <div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

    <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">

    <div class="row">

    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

    <div class="col-lg-6">

    <div class="p-5">

    <div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">
    Accesso Sistema Monitoraggio Ambientale
    </h1>
    </div>

<<<<<<< HEAD
    <form class="user" onsubmit="login(event)">

    <div class="form-group">
    <input type="email"
    class="form-control form-control-user"
    id="exampleInputEmail"
    placeholder="Inserisci email">
    </div>

    <div class="form-group">
    <input type="password"
    class="form-control form-control-user"
    id="exampleInputPassword"
    placeholder="Password">
    </div>
=======
<form method="post" action="auth.php">

<div class="form-group">
<input type="email"
class="form-control form-control-user"
placeholder="Inserisci email" 
name="email" required>
</div>

<div class="form-group">
<input type="password"
class="form-control form-control-user"
placeholder="Password" 
name="password" required>
</div>
>>>>>>> 952475e5957c3f19ca9976061a255bdeb93ae5f8

    <button type="submit"
    class="btn btn-primary btn-user btn-block">
    Accedi alla Dashboard
    </button>

    </form>

    <hr>

    <div class="text-center">
    <a class="small" href="register.html">
    Crea un account
    </a>
    </div>

    </div>
    </div>

    </div>
    </div>
    </div>

    </div>

    </div>

    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

<<<<<<< HEAD
    <script>

    function login(event){

    event.preventDefault()

    let email = document.getElementById("exampleInputEmail").value
    let password = document.getElementById("exampleInputPassword").value

    if(email === "admin@email.com" && password === "1234"){

    localStorage.setItem("logged","true")

    window.location.href = "index.php"

    }else{

    alert("Email o password non corretti")

    }

    }

    </script>

    </body>
=======
</body>
>>>>>>> 952475e5957c3f19ca9976061a255bdeb93ae5f8

    </html>
    ```

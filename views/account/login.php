<?php

include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php');

$hostname = "mssql2.iproject.icasites.nl"; //Naam van de Server
$dbname = "iproject42";    //Naam van de Database
$username = "iproject42";      //Inlognaam
$pw = "7MqNNSxC";      //Password

//$pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");

function checkEmptyFields(){
    global $errors;
    global $vars;
    $errors['username']    = ($vars['username'] == "")    ?  "vul je gebruikersnaam in aub.":'';
    $errors['password']    = ($vars['password'] == "")    ?  "vul je wachtwoord in aub.":'';
}

function getRealPOST() {
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}


ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $vars = getRealPost();
    checkEmptyFields();
    if(isset($vars['username']) && isset($vars['password'])){
        $username = $vars['username'];
        $stmt = $pdo->prepare("SELECT password FROM Users WHERE username = ?");
        $stmt->execute([$vars['username']]);
        $data = $stmt->fetch();

        if($data){
            $password_ok = password_verify($vars['password'], $data['password']);
            if($password_ok){
                session_start();
                $_SESSION['username'] = $username;
                header('location: home.php');
            }
            else {
                $errors['password'] = "Onjuist wachtwoord";
                $errors['username'] = " ";
            }
        }
        else {
            $errors['password'] = " ";
            $errors['username'] = "Onbekend gebruikersnaam";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registratie</title>
    <link rel="stylesheet" href="Css.css">
</head>
<body>
<div class="lj-overlay lj-overlay-color"></div>
<div>
    <div>
        <h2 class="title">EenmaalAndermaal
            <br>
            <br>
            Login</h2>
    </div>
    <div class="container">

        <form class="form-horizontal sign-in-form" action="#" method="POST">
            <div class="form-group row <?php global $errors; print((!empty($errors['username']))?'has-danger"':'"'); ?>>
<!--        <label for="username" class="col-4 col-form-label"></label>-->
            <div class="input-group inputform row" >
                <span class="input-group-addon" id="basicaddon1"><img class="rounded" src="11-user---single.png" class="img-responsive"></span>
                <input type="text" id="username" name="username" class="form-control" placeholder="Gebruikersnaam" autofocus>
            </div>
            <div class="form-control-feedback"><?php global $errors; echo $errors['username']?></div>
    </div>

    <div class="form-group row <?php global $errors; print((!empty($errors['password']))?'has-danger"':'"'); ?>>
<!--        <label for="name" class="col-4 col-form-label"></label>-->
    <div class="input-group inputform row" >
        <span class="input-group-addon" id="basicaddon1"><img src="36-user---password-lock.png" class="img-responsive"></span>
        <input type="password" id="password" name="password" class="form-control" placeholder="Wachtwoord">
    </div>
    <div class="form-control-feedback"><?php global $errors; echo $errors['password']?></div>
</div>

<div class="form-group row inputform" style="margin:auto;">
    <label class="col-sm-4 control-label"></label>
    <div>
        <button type="submit" class="btn btn-success btn-block">Login</button>
    </div>
</div>
</form>
</div>

<footer class="footer">
    <p class="copyright">&copy; 2017 - EenmaalAndermaal. Alle rechten voorbehouden</p>
    <p class="author">Aaron Burden, Unsplash</p>
</footer>
</body>
</html>
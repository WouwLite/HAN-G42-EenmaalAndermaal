<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/app.php");

// Start a session
session_start();

// Check if user is already logged on. If yes, redirect to accountpage.
if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

function checkEmptyFields()
{
    global $errors;
    global $vars;
    $errors['username'] = ($vars['username'] == "") ? "vul je gebruikersnaam in aub." : '';
    $errors['password'] = ($vars['password'] == "") ? "vul je wachtwoord in aub." : '';
}

function getRealPOST()
{
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vars = getRealPost();
    checkEmptyFields();
    if (isset($vars['username']) && isset($vars['password'])) {
        $username = $vars['username'];
        $stmt = $pdo->prepare("SELECT password FROM Users WHERE username = ?");
        $stmt->execute([$vars['username']]);
        $data = $stmt->fetch();

        if ($data) {
            $password_ok = password_verify($vars['password'], $data['password']);
            if ($password_ok) {
                session_start();
                $_SESSION['username'] = $username;
                header('location: index.php');
            } else {
                $errors['password'] = "Onjuist wachtwoord";
                $errors['username'] = " ";
            }
        } else {
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
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/css/register.css">
<!--    <link rel="stylesheet" href="--><?php //$_SERVER['DOCUMENT_ROOT'] ?><!--/assets/css/login-register.css">-->
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php'); ?>
</head>
<body>

<div class="bg-overlay">
    <div class="container col-md-8 col-xs-6 jumbotron" style="background: rgba(236, 240, 241, 0.9);">
        Ok, de quote (") zit ergens verstopt in de bg-overlay of jumbotrol
        <a href="<?= $app_url ?>" class="btn btn-danger" role="button" aria-pressed="true"
           style="margin-left: 5px;margin-top: 5px;">Terug naar thuis</a>
        <div>
            <br><br>
            <center><img src="/storage/images/logo/logo-ea-groot-donker.png" style="max-height: 70px"
                         alt="EenmaalAndermaal Logo"></center>
            <br><br>
        </div>
        <div class="container">
            <form class="form-horizontal sign-in-form" action="#" method="POST">
                <div class="alert alert-info" role="alert">
                    Heb je nog geen account?<br> <a href="<?= $app_url ?>/views/account/register.php">klik dan hier om te
                        registreren</a>
                </div>
                <div <?php global $errors;
                print((!empty($errors['username'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                    <div class="input-group inputform">
                        <span class="input-group-addon fa fa-user"></span>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Gebruikersnaam"
                               autofocus>
                    </div>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['username'] ?></div>
                </div>

                <div <?php global $errors;
                print((!empty($errors['password'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                    <div class="input-group inputform">
                        <span class="input-group-addon fa fa-lock" id="basicaddon1"></span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Wachtwoord">
                    </div>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['password'] ?></div>
                </div>

                <div class="form-group row inputform" style="margin:auto;">
                    <label class="col-sm-4 control-label"></label>
                    <div>
                        <button type="submit" class="btn btn-success btn-block">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <p class="copyright">&copy; 2017 - EenmaalAndermaal. Alle rechten voorbehouden</p>
    <p class="author">Aaron Burden, Unsplash</p>
</footer>
</body>
</html>
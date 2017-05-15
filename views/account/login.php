<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registratie</title>
    <link rel="stylesheet" href="Css.css">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>
</head>
<div>
    <div>
        <h2 class="title">EenmaalAndermaal
            <br>
            <br>
            Login</h2>
    </div>
    <div class="container">
        <form class="form-horizontal sign-in-form" action="#" method="POST">
            <div class="form-group row <?php global $errors; print((!empty($errors['username']))?'has-danger"':'"'); ?>">
                <div class="input-group inputform row" >
                    <span class="input-group-addon" id="basicaddon1"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Gebruikersnaam" autofocus>
                </div>
                <div class="form-control-feedback"><?php global $errors; echo $errors['username']?></div>
            </div>

            <div class="form-group row <?php global $errors; print((!empty($errors['password']))?'has-danger"':'"'); ?>">
                <div class="input-group inputform row" >
                    <span class="input-group-addon" id="basicaddon1"><i class="fa fa-lock" aria-hidden="true"></i></span>
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
</div>

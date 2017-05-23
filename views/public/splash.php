<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

// Start session
session_start();

// If user is logged on, redirect to 'normale' homepage.
if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?=$title?></title>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/css/vendor/bootstrap.min.css') ?>
    <link href="<?=$app_url?>/assets/css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$app_url?>/assets/css/splash.css" rel="stylesheet">

    <!--  Super short CSS code for the fancy search bar  -->
    <style>
        #homeSearchBox		{
            background: #ededed url(http:////cdn.wouwlite.eu/icasites.nl/storage/images/elements/magnifier.svg) no-repeat 8px center;
            width:50%;
            height:50px;
            /*-webkit-transition:all 0.4s ease-in-out;*/
            -webkit-transition: all .5s;
            -moz-transition: all .5s;
            transition: all .5s;
        }
        #homeSearchBox:focus	{
            width:100%;
            height:80px;
            font-size: 200%;
            /*-webkit-transition:all 0.6s ease-in-out;*/
            background: white url(http:////cdn.wouwlite.eu/icasites.nl/storage/images/elements/magnifier.svg) no-repeat 16px center;
            /*background-color: #fff;*/

            -webkit-box-shadow: 0 0 5px rgba(109,207,246,.5);
            -moz-box-shadow: 0 0 5px rgba(109,207,246,.5);
            box-shadow: 0 0 5px rgba(109,207,246,.5);
        }
    </style>

</head>

<body>

<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <p class="masthead-brand"><img src="<?=$cdn_url?>/storage/images/logo/logo-ea-groot-licht.png" style="max-height: 70px" alt="EenmaalAndermaal Logo"></p>
                    <nav class="nav nav-masthead">
                        <a class="nav-link" href="<?=$app_url?>/views/public/">Thuis</a>
                        <a class="nav-link" href="<?=$app_url?>/views/account/login.php">Account</a>
                        <a class="nav-link" href="<?=$app_url?>/views/public/contact.php">Contact</a>
                    </nav>
                </div>
            </div>

            <div class="inner cover">
                <h1 class="cover-heading"><?=$title?></h1>
                <p class="lead"><?=$slogan?></p>
                <p class="lead">
                    <input class="form-control input-lg" id="homeSearchBox" placeholder="Zoek naar biedingen..." type="text" style="margin:auto;text-align:center;">
                </p>
                <p class="lead">
                    <a class="btn btn-lg btn-primary" href="#"><i class="fa fa-magnifier fa-lg"></i> Zoeken</a>
                </p>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p><a href="index.php"><i class="fa fa-gavel" aria-hidden="true"> Overzicht populaire biedingen</a> </p>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="<?=$APP_URL?>/assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="<?=$app_url?>/assets/js/vendor/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?=$app_url?>/assets/js/vendor/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

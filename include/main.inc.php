<!-- /resources/include/main.inc.php -->
<?php
//session_start();
//include_once ($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dev/functions.dev.php');
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?=$title?></title>
    <meta name="description" content="EenmaalAndermaal Veilingen">
    <meta name="author" content="iConcepts BV">

    <!-- /resources/include/styles.inc.php -->

</head>

<body>
    <!-- ************************* -->
    <!--  TOP / HORIZONTAL NAVBAR  -->
    <!-- ************************* -->
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded" style="background-color: #5484A4;">
        <!-- Logo -->

        <a class="navbar-brand" href="/views/public/">
            <img src="//securehub.eu/dl/EA-LogoV3.svg" height="70px" style="margin-top: -12px; margin-bottom: -12px" alt="">
        </a>
        <!-- Searchform -->
        <div class="col-sm-1"></div>
        <form>
            <input class="form-control sm-2" type="search" id="search" name="Search" placeholder="Zoek naar veiling..."/>
        </form>
        <div class="col-sm-1"></div>
        <!-- Links -->
            <!-- mr-auto >> margin right auto, aligns the div to left -->
            <!-- ml-auto >> margin left auto, aligns the div to right -->
        <ul class="navbar-nav ml-auto">
            <!-- ADD IF STATEMENT TO SHOW ADD-NEW BUTTON. ELSE HIDE -->
            <li class="nav-item">
                <a class="nav-link text-white" href="#new-add"><i class="fa fa-plus fa-inverse" aria-hidden="true"></i> Nieuw</a>
            </li>
                <?php if (!empty($users) || $debug): ?>
                    <li class="nav-item">
                    <a class="nav-link text-white" href="<?=$cdn_url?>/views/account"><i class="fa fa-user fa-inverse" aria-hidden="true"></i> Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?=$cdn_url?>/views/account/logout.php"><i class="fa fa-power-off fa-inverse" aria-hidden="true"></i> Afmelden</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?=$cdn_url?>/views/account/register.php"><i class="fa fa-user-plus fa-inverse" aria-hidden="true"></i> Registreren</a>
                    </li>
                <?php endif; ?>
        </ul>
    </nav>

    <!-- **************************************** -->
    <!--  HERE STARTS THE MAIN CONTENT-CONTAINER  -->
    <!-- **************************************** -->

    <div id="content">


<!-- /resources/include/main.inc.php -->
<?php
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
$rootpath = $_SERVER['SERVER_NAME'];
$css = "/resources/assets/css";
$views = "/resources/views";

$title = "EenmaalAndermaal";
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?=$title?></title>
    <meta name="description" content="EenmaalAndermaal Veilingen">
    <meta name="author" content="iConcepts BV">

    <!-- /resources/include/styles.inc.php -->
    <!-- Local Stylesheets -->
    <link type="text/css" rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME'] ?>/assets/css/main.css">
    <link type="text/css" rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME'] ?>/assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME'] ?>/assets/css/search.css">
    <link type="text/css" rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME'] ?>/assets/css/sidebar.css">
    <link type="text/css" rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME'] ?>/assets/css/footer.css">

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://use.fontawesome.com/68afb4fb20.css">

    <!-- External Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito|Nunito+Sans|Comfortaa" rel="stylesheet">

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded" style="background-color: #5484A4;">
        <!-- Logo -->

        <a class="navbar-brand" href="#">
            <img src="//securehub.eu/dl/EA-LogoV3.svg" height="70px" style="margin-top: -12px; margin-bottom: -12px" alt="">
        </a>
        <!-- Searchform -->
        <div class="col-sm-1"></div>
        <form>
            <input class="form-control sm-2" type="search" id="search" name="Search" placeholder="Zoek naar veiling..."/>
        </form>
        <!-- Links -->
            <!-- mr-auto >> margin right auto, aligns the div to left -->
            <!-- ml-auto >> margin left auto, aligns the div to right -->
        <ul class="navbar-nav ml-auto" style="color: #ffffff;">
            <!-- ADD IF STATEMENT TO SHOW ADD-NEW BUTTON. ELSE HIDE -->
            <li class="nav-item">
                <a class="nav-link" href="#new-add"><i class="fa fa-plus fa-2x text-success" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#account"><i class="fa fa-user fa-2x fa-inverse" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#logout"><i class="fa fa-power-off fa-2x fa-inverse" aria-hidden="true"></i></a>
            </li>
        </ul>
    </nav>

    <div id="sidebar">
        <ul>
            <li><strong>Dashboard</strong></li>
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fa fa-home" aria-hidden="true"></i> Thuis</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-star" aria-hidden="true"></i> Populair</a>
            </li>
            <li><span class="sidebar-span"></span></li>
            <li><strong>Account</strong></li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-gavel" aria-hidden="true"></i> Mijn biedingen <span class="badge badge-info">3</span></a>
            </li>
            <!-- Create IF statement. If user is merchant, show this link, else hide -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Mijn advertenties <span class="badge badge-info">1</span></a>
            </li>
            <li><span class="sidebar-span"></span></li>
            <li><strong>Rubrieken</strong></li>
            <!-- Create FOREACH with categories from DB -->
            <li class="nav-item">
                <a class="nav-link" href="#">Auto's</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Computers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Scrumboarden</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Games</a>
            </li>
        </ul>

        <div id="sidebar-hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <script src="http://localhost/assets/js/vendor/jquery-1.11.2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebar-hamburger').click(function () {
                $('#sidebar').toggleClass('visible');
            });
        });
    </script>

<br><br>THIS IS THE BODY WHERE THE CONTENT GOES! DO NOT EDIT THIS TEMPLATE TO ADD CONTENT, USE THE INCLUDE / REQUIRE FUNCTION!!!!

    <a class="btn btn-default" href="path/to/settings" aria-label="Settings">
        <i class="fa fa-cog" aria-hidden="true"></i>
    </a>

    <a class="btn btn-danger" href="path/to/settings" aria-label="Delete">
        <i class="fa fa-trash-o" aria-hidden="true"></i>
    </a>

    <a class="btn btn-primary" href="#navigation-main" aria-label="Skip to main navigation">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </a>

</body>
</html>
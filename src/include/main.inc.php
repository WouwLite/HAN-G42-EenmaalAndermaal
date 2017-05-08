<!-- /resources/include/main.inc.php -->
<?php
$live = '0'; // Set 'Production' or 'Development' mode. 0 = 'Development', 1 = 'Production'
//session_start();
//include_once ($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dev/functions.dev.php');

$title = "EenmaalAndermaal";

// Check if in production phase or live. Change file-routes
if ($live == 0) {
    $stylepath = '//localhost/';
} else {
    $stylepath = '//cdn.wouwlite.eu/icasites.nl/';
}
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

    <!-- External Stylesheets -->
    <link type="text/css" rel="stylesheet" href="<?=$stylepath?>assets/css/main.css">
    <link type="text/css" rel="stylesheet" href="//cdn.wouwlite.eu/icasites.nl/assets/css/vendor/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="<?=$stylepath?>assets/css/search.css">
    <link type="text/css" rel="stylesheet" href="<?=$stylepath?>assets/css/sidebar.css">
    <link type="text/css" rel="stylesheet" href="<?=$stylepath?>assets/css/footer.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/68afb4fb20.css">

    <!-- External Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito|Nunito+Sans|Comfortaa" rel="stylesheet">

</head>

<body>
    <!-- ************************* -->
    <!--  TOP / HORIZONTAL NAVBAR  -->
    <!-- ************************* -->
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
        <div class="col-sm-1"></div>
        <!-- Add warning indicator for developmentpurposes -->
        <?php
            if ($dev_visible == 1) {
                if ($live == 1) {
                    $status = "Production";
                    echo 'Status: <span class="badge badge-success">' . $status . '</span> Path: ' . $stylepath;
                } else {
                    $status = "Development";
                    echo 'Status: <span class="badge badge-danger">' . $status . '</span> Path: ' . $stylepath;
                }
            }
        ?>
        <!-- Links -->
            <!-- mr-auto >> margin right auto, aligns the div to left -->
            <!-- ml-auto >> margin left auto, aligns the div to right -->
        <ul class="navbar-nav ml-auto" style="color: #ffffff;">
            <!-- ADD IF STATEMENT TO SHOW ADD-NEW BUTTON. ELSE HIDE -->
            <li class="nav-item">
                <a class="nav-link" href="#new-add"><i class="fa fa-plus fa-2x text-success" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//localhost/views/account"><i class="fa fa-user fa-2x fa-inverse" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#logout"><i class="fa fa-power-off fa-2x fa-inverse" aria-hidden="true"></i></a>
            </li>
        </ul>
    </nav>

    <!-- **************************************** -->
    <!--  HERE STARTS THE MAIN CONTENT-CONTAINER  -->
    <!-- **************************************** -->

    <div id="content">
        <ol class="breadcrumb">
            <?php
                foreach ($breadcrumbs as $bc) {
                    echo "<li class='breadcrumb-item'><a href='#'>" . $bc . "</a></li>";
                }
                echo "<li class='breadcrumb-item active'>" . $breadcrumb_active . "</li>";
            ?>
        </ol>
        <?=$getContent?>
    </div>

    <!-- *********************************** -->
    <!--  END OF THE MAIN CONTENT-CONTAINER  -->
    <!-- *********************************** -->

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

    <!-- FOOTER WERKT NIET, IVM CONTAINEROVERLAY OP VERKEERD NIVEAU. -->
    <footer>
        <p>Hello world!</p>
    </footer>

    <!--    <script src="http://cdn.wouwlite.eu/han/src/assets/js/vendor/jquery-1.11.2.min.js"></script>-->
    <script src="//localhost/assets/js/vendor/jquery-1.11.2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebar-hamburger').click(function () {
                $('#sidebar').toggleClass('visible');
                $('#content').toggleClass('visible');
            });
        });
    </script>

</body>
</html>
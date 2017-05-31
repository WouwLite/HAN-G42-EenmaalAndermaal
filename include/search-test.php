<?php include($_SERVER['DOCUMENT_ROOT'] . '/config/app.php'); ?>
<!doctype html>
<html>
    <head>
        <title>Test Live Search</title>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php'); ?>
    </head>
    <body>
        <form id="live-search" action="" class="styled" method="post">
            <fieldset>
                <input type="text" class="text-input" id="filter" value="" />
                <span id="filter-count"></span>
            </fieldset>
        </form>

        <nav>
            <ul>
                <li><a href="#">Jim James</a></li>
                <li><a href="#">Hello Bye</a></li>
                <li><a href="#">Wassup Food</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Bleep bloop</a></li>
                <li><a href="#">jQuery HTML</a></li>
                <li><a href="#">CSS HTML AJAX</a></li>
                <li><a href="#">HTML5 Net Set</a></li>
                <li><a href="#">Node Easy</a></li>
                <li><a href="#">Listing Bloop</a></li>
                <li><a href="#">Contact HTML5</a></li>
                <li><a href="#">CSS3 Ajax</a></li>
                <li><a href="#">ET</a></li>
            </ul>
        </nav>

        <script src="<?=$app_url?>/assets/js/livesearch.js"></script>
    </body>
</html>
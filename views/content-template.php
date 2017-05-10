<!-- /views/content-template.php -->

<!-- **************************************************************** -->
<!-- * Welkom bij de Content Template. Graag deze pagina            * -->
<!-- * niet aanpassen! Kopieer onderstaande code naar               * -->
<!-- * je eigen document en bouw daar verder.                       * -->
<!-- **************************************************************** -->

<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

$hello = "Hello world!";

?>



<!-- **************************************** -->
<!--  HERE STARTS THE MAIN CONTENT-CONTAINER  -->
<!-- **************************************** -->
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
    <li class="breadcrumb-item"><a href="#">Developer</a></li>
    <li class="breadcrumb-item active">Template</li>
</ol>

<h1>Pagina titel</h1>
<p>
    <?=$hello?><br>
    This is dummy-data from 'functions.dev.php': <strong><?=$testName?></strong>
</p>

<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
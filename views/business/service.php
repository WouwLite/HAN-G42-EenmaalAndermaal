<!-- /views/public/service.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

$pageTitle = 'Service';

?>
<div class="container-float">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
        <li class="breadcrumb-item"><a href="<?=$app_url?>/views/business/about.php">Bedrijf</a></li>
        <li class="breadcrumb-item active"><?=$pageTitle?></li>
    </ol>
</div>
<div class="container">
    <h1><?=$pageTitle?></h1>
    <p>
        Sorry, deze pagina is nog niet beschikbaar!
    </p>
</div>
<br>
<br>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
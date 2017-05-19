<!-- /views/public/index.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

?>
<div class="container-float">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
        <li class="breadcrumb-item active">Nieuwste advertenties</li>
    </ol>
</div>

<div class="jumbotron">
    <h1 class="display-3">Hello, world!</h1>
    <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
    <hr class="my-4">
    <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
    </p>
</div>

<div class="container-float">
    <h1>Hello world!</h1>
    <p>
        Here comes the dynamic content.
    </p>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
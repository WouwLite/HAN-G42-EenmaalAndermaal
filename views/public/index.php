<!-- /views/public/index.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

// Start session
//session_start();

?>
<div class="container-float">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
        <li class="breadcrumb-item active">Nieuwste advertenties</li>
    </ol>
</div>
<?php
// If user is logged on, don't show this jumbotron / call-to-action
if (!isset($_SESSION['username'])): ?>
    <div class="jumbotron">
    <h1 class="display-3">Word nu lid!</h1>
        <p class="lead"><?=$title?> is hét platform voor het aanbieden van je producten. Van nieuw tot oud, alles kan! Én als klap op de vuurpijl: het is volledig <span class="badge badge-primary">GRATIS</span>.</p>
    <p class="lead">
        <a class="btn btn-outline-primary btn-lg btn-block" href="<?=$app_url?>/views/account/register.php" role="button">Registreren</a>
    </p>
    </div>
<?php endif; ?>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/showAds.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
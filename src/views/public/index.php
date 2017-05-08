<!-- /src/views/content-template.php -->

<!-- **************************************************************** -->
<!-- * Welkom bij de Content Template                               * -->
<!-- * Graag deze pagina niet aanpassen                             * -->
<!-- * Kopieer onderstaande code naar je eigen document en          * -->
<!-- * bouw daar verder                                             * -->
<!-- * Check de documentatie van PHP over Heredoc als je            * -->
<!-- * PHP variables wilt toevoegen. Gebruik {} -brackets voor PHP  * -->
<!-- **************************************************************** -->

<?php
/*
 * Voer hieronder eventuele extra PHP variables toe
 */
// Voegt developmentfuncties toe, svp niet verwijderen
include($_SERVER['DOCUMENT_ROOT'] . '/dev/functions.dev.php');

// Voeg breadcrumbs toe (https://v4-alpha.getbootstrap.com/components/breadcrumb/)
$breadcrumbs        = array("Thuis");
$breadcrumb_active  = "Laatste biedingen";

/*
 * Einde PHP variable-area
 */

$getContent = <<<EOD
    <!-- **************************************** -->
    <!--  HERE STARTS THE MAIN CONTENT-CONTAINER  -->
    <!-- **************************************** -->
    
    <h1>Laatste biedingen</h1>
    <p>
        Welkom op EenmaalAndermaal! Dit is dummydata van functions.dev.php: <strong>{$testName}</strong>
    </p>
    
    <!-- *********************************** -->
    <!--  END OF THE MAIN CONTENT-CONTAINER  -->
    <!-- *********************************** -->
EOD;

// Nadat de variabele $getContent middels heredoc invulling heeft gekregen,
// include de template - waar vervolgens de $getContent wordt uitgevoerd.
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

?>
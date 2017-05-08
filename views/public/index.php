<!-- /views/public/index.php -->

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
        Welkom op EenmaalAndermaal! Dit is dummydata van functions.dev.php: <strong>{$testName}</strong><br>
        IK WIL EEN BACK-END FRAMEWORK!!!! <-- Dat waren de frustraties, ééééén door! :-).
    </p>
    
    <!-- *********************************** -->
    <!--  END OF THE MAIN CONTENT-CONTAINER  -->
    <!-- *********************************** -->
EOD;

// Nadat de variabele $getContent middels heredoc invulling heeft gekregen,
// include de template - waar vervolgens de $getContent wordt uitgevoerd.
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

?>
<?php
$getBreadcrumbs = <<<EOD
<!-- Pas breadcrumb aan naar juiste pad -->
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Thuis</a></li>
    <li class="breadcrumb-item"><a href="#">Mijn Account</a></li>
    <li class="breadcrumb-item active">Overzicht</li>
</ol>

<!-- **************************************** -->
<!--  HERE STARTS THE MAIN CONTENT-CONTAINER  -->
<!-- **************************************** -->

<h1>Pagina titel</h1>
<p>
    Hello world!
</p>

<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->
EOD;

// Nadat de variabele $getContent middels heredoc invulling heeft gekregen,
// include de template - waar vervolgens de $getContent wordt uitgevoerd.
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');
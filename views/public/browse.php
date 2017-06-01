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
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
function getAds()
{
    global $pdo;
    if (isset($_GET['Search'])) {
        $sql = "select *
From Object o
WHERE FREETEXT(title,?) or Categories = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['Search']??' ', $_GET['cat']??'']);
    } else {
        $sql = "select *
From Object o
WHERE Categories = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['cat']??'']);
    }
    return $stmt->fetchAll();
}

//CROSS APPLY
//(SELECT TOP 1 productid, filename
//From productPhoto pp where o.productid=pp.productid) productPhoto

/*
$Ads = getAds();
$i = 0;
echo "<div class='container'>";
foreach ($Ads as $Adverts) {
    if ($i == 0) {
        echo "<div class='row'>";
    }
    $i++;
    echo "<div class='col-sm-4'>";
    echo "<h2>" . $Adverts[0] . "</h2>";
    if ($i == 5) {
        $i = 0;
        echo "</div>";
    }
    echo "</div>";
}
echo "</div>";
echo "</div>";
*/
?>
<!-- Page Content -->

<div class="container">
    <header class="jumbotron hero-spacer">
        <h1>Our Latest Items!</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, ipsam, eligendi, in quo sunt possimus non
            incidunt odit vero aliquid similique quaerat nam nobis illo aspernatur vitae fugiat numquam repellat.</p>
    </header>


    <div class="row text-center">
        <?php
        $ads = getAds();
        foreach ($ads as $value) {
            ?>
            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="img-thumbnail">
                    <img src="http://placehold.it/800x500"
                         class="img-fluid"
                         alt="<?php echo $value[3] ?>">
                    <div class="figure-caption">
                        <h3>
                            <?php echo substr($value[1], 0, 30) ?>
                        </h3>
                        <p><?php echo substr($value[2], 0, 50) ?>... </p>
                        <p>
                            <a class="btn btn-primary"
                               href="<?= $app_url ?>/views/public/productPage.php?<?php echo $value[0] ?>">Bied
                                Nu</a>
                            <a class="btn btn-secondary"
                               href="<?= $app_url ?>/views/public/productPage.php?<?php echo $value[0] ?>">Meer
                                Info</a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</div>


<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
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
function getAds() {
    global $pdo;
    if (!empty($_GET['Search']) and !empty($_GET['cat'])) {
        $sql = "select *
From Object o
WHERE FREETEXT(title,?) and Categories = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['Search']??' ', $_GET['cat']??'']);
    } elseif (!empty($_GET['Search'])) {
        $sql = "select *
From Object o
WHERE FREETEXT(title,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['Search']??' ']);
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
    <h2>Zoekresultaten</h2>
    <div class="row text-center">
        <?php
        $ads = getAds();
        $lastValue = end($ads);
        reset($ads);
        foreach (array_slice($ads, (($_GET['page']??1) - 1) * 16, 16) as $value) {
            global $lastPage;
            if ($value === $lastValue) $lastPage = true;
            $picsql = "SELECT TOP 1 filename FROM productPhoto WHERE productid = ?";
            $stmt = $pdo->prepare($picsql);
            $stmt->execute([$value[0]]);
            $thumbnail = $stmt->fetchColumn();
            ?>
            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="img-thumbnail">
                    <?php
                    if (substr($thumbnail, 0, 3) === "dt_") {
                        $picsource = "http://iproject42.icasites.nl/pics/";
                    } else {
                        $picsource = "http://iproject42.icasites.nl/uploads/";
                    }
                    ?>
                    <img style="max-width: 100%; height: 200px;" src="<?= $picsource ?><?= $thumbnail; ?> "
                         class="img-fluid"
                         alt="<?php echo $value[1] ?>">
                    <div class="figure-caption">
                        <h3>
                            <?php echo substr($value[1], 0, 30) ?>
                        </h3>
                        <p><?php echo substr($value[2], 0, 50) ?>... </p>
                        <p>
                            <a class="btn btn-primary"
                               href="<?= $app_url ?>/views/public/productPage.php?link=<?php echo $value[0] ?>">Bied
                                Nu</a>
                            <a class="btn btn-secondary"
                               href="<?= $app_url ?>/views/public/productPage.php?link=<?php echo $value[0] ?>">Meer
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
<br>
<br>
<div class="text-center">
    <?php if (key_exists('page', $_GET) and $_GET['page']??0 > 0): ?>
        <a class="btn btn-primary"
           href="<?= key_exists('Search',$_GET)?"?Search=".$_GET['Search']:"?cat=".$_GET['cat'];?>&page=<?=($_GET['page']??1) - 1 ;?>">Vorige pagina</a>
    <?php else: ?>
        <a class="btn btn-primary disabled">Vorige pagina</a>
    <?php endif ?>
    <?php global $lastPage;
    if (!$lastPage): ?>
        <a class="btn btn-primary"
           href="<?= key_exists('Search', $_GET) ? "?Search=" . $_GET['Search'] : '?cat=' . $_GET['cat']; ?>&page=<?= ($_GET['page']??1) + 1; ?>">Volgende
            pagina</a>
    <?php else: ?>
        <a class="btn btn-primary disabled">Volgende pagina</a>
    <?php endif ?>
<br>
<br>
<br>
</div>
</div>


<!-- *********************************** -->
<!--  END OF THE MAIN CONTENT-CONTAINER  -->
<!-- *********************************** -->

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
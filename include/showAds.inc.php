<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
function getAds()
{
    global $pdo;
    $sql = "select top 24 o.productid, Title, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories, productPhoto.filename
From Object o
CROSS APPLY
(SELECT TOP 1 productid, filename
From productPhoto pp where o.productid=pp.productid) productPhoto";
    $result = $pdo->query($sql);
    $Ads = array();
    while ($row = $result->fetch()) {
        $Ad = array($row['Title'], $row['description'], $row['productid'], $row['filename']);
        $Ads[] = $Ad;
    }
    return $Ads;
}

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
                $picsql = "SELECT TOP 1 filename FROM productPhoto WHERE productid = ?";
                $stmt = $pdo->prepare($picsql);
                $stmt->execute([$value[2]]);
                $thumbnail = $stmt->fetchColumn();
                ?>
                <div class="col-md-3 col-sm-6 hero-feature">
                    <div class="img-thumbnail">
                        <?php
                        if (substr($thumbnail, 0, 3) === "dt_") {
                            $picsource = "http://iproject42.icasites.nl/pics/";
                        } else {
                            $picsource = "http://iproject42.icasites.nl/views/merchant/AdImages/";
                        }
                        ?>
                        <img src="<?= $picsource ?><?= $thumbnail; ?>"
                             class="img-fluid"
                             alt="<?php echo $value[3] ?>">
                        <div class="figure-caption">
                            <h3>
                                <?php echo substr($value[0], 0, 30) ?>
                            </h3>
                            <p><?php echo substr($value[1], 0, 50) ?>... </p>
                            <p>
                                <a class="btn btn-primary"
                                   href="<?= $app_url ?>/views/public/productPage.php?<?php echo $value[2] ?>">Bied
                                    Nu</a>
                                <a class="btn btn-secondary"
                                   href="<?= $app_url ?>/views/public/productPage.php?<?php echo $value[2] ?>">Meer
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


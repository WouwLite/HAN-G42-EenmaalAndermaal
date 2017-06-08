<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
function getAds()
{
    global $pdo;
    $sql = "select DISTINCT top 24 o.productid, Title, description, durationendDay, durationendTime, Categories, productPhoto.filename, startprice, (select max(biddingprice)
																																from Bidding b
																																where o.productid = b.productid) as biddingprice
From Object o
CROSS APPLY
(SELECT TOP 1 productid, filename
From productPhoto pp where o.productid=pp.productid) productPhoto left outer join Bidding b on o.productid = b.productid
where durationendDay >= getDate()
AND auctionclosed = 0
order by durationendDay, durationendTime";
    $result = $pdo->query($sql);
    $Ads = array();
    while ($row = $result->fetch()) {
        $Ad = array($row['Title'], $row['description'], $row['productid'], $row['filename'], $row['biddingprice'], $row['startprice']);
        $Ads[] = $Ad;
    }
    return $Ads;
}


?>
<!-- Page Content -->

<div class="container">
    <header class="jumbotron hero-spacer">
        <h1>Nieuwe veilingen</h1>
        <p>Gaaf, de aller nieuwste veiligingen staan op deze pagina! Kijk snel of het product wat je zoekt in dit overzicht staat of gebruik de zoekfunctie als je opzoek bent naar iets specifieks.</p>
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
                    <div class="img-thumbnail ">
                        <?php
                        if (substr($thumbnail, 0, 3) === "dt_") {
                            $picsource = "http://iproject42.icasites.nl/pics/";
                        } else {
                            $picsource = "http://iproject42.icasites.nl/uploads/";
                        }
                        ?>
                        <div class="text-center">
                        <img style="max-width: 100%; height: 200px;" src="<?= $picsource ?><?= $thumbnail; ?>"
                             class="img-fluid"
                             alt="<?php echo $value[3] ?>">
                        </div>
                        <div class="figure-caption">
                            <h3>
                                <?php echo substr($value[0], 0, 30) ?>
                            </h3>
                            <p><?php echo substr($value[1], 0, 50) ?>... </p>
                            <p>
                                <a class="btn btn-primary"
                                   href="<?= $app_url ?>/views/public/productPage.php?link=<?php echo $value[2] ?>">Bied
                                    Nu</a>
                                <a class="btn btn-secondary"
                                   href="<?= $app_url ?>/views/public/productPage.php?link=<?php echo $value[2] ?>">Meer
                                    Info</a>
                            </p>

                        </div>
                        <?php if (!empty($value[4])) {
                            echo "<span class='badge badge-pill badge-success'>Huidig bod: € " . $value[4]."</span>";
                        } else{
                            echo "<span class='badge badge-pill badge-warning'>Begin bieden vanaf: € ". $value[5]."</span>";
                        }?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<br>
<br>
</div>


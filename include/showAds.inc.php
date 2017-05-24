<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
function getAds()
{
    global $pdo;
    $sql = "select top 25 o.productid, Title, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories, pp.filename
from Object o left outer join productPhoto pp on o.productid=pp.productid";
    $result = $pdo->query($sql);
    $Ads = array();
    while ($row = $result->fetch()) {
        $Ad = array($row['Title'], $row['description'], $row['Categories'], $row['filename']);
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
    <div class="row">
        <div class="col-md-9">
            <div class="row carousel-holder">
                <div class="col-md-12">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img class="slide-image" src="<?= $app_url ?>/storage/images/Dscn7471_sunset-sundog_crop_800x300.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="slide-image" src="<?= $app_url ?>/storage/images/Dscn7471_sunset-sundog_crop_800x300.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="slide-image" src="<?= $app_url ?>/storage/images/Dscn7471_sunset-sundog_crop_800x300.jpg" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-example-generic" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-example-generic" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                $ads = getAds();
                foreach ($ads as $value) {
                    ?>
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="img-thumbnail">
                            <img src="http://placehold.it/320x150" class="img-fluid" alt="">
                            <div class="figure-caption">
                                <h4><a href="#"><?php echo substr($value[0], 0, 30) ?></a></h4>
                                <p><?php echo substr($value[1], 0, 100) ?>... </p>
                            </div>
                            <div class="ratings">
                                <p class="pull-rigght">15 reviews</p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>
</div>


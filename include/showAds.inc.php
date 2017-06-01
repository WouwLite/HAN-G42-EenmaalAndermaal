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
                    <p><?php if (!empty($value[4])) {
                            echo "<span class='badge badge-pill badge-success'>Huidig bod: € " . $value[4] . "</span>";
                        } else {
                            echo "<span class='badge badge-pill badge-warning'>Begin bieden vanaf: € " . $value[5] . "</span>";
                        } ?></p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</div>


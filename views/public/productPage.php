<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

function getAd()
{
    $url = $_GET['link'];
    global $pdo;
    $result = $pdo->query("select o.productid, Title, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories, pp.filename, (select max(biddingprice)
																																from Bidding b
																																where o.productid = b.productid) as biddingprice
from Object o left outer join productPhoto pp on o.productid=pp.productid
where o.productid like '%$url%'");
    $content = array();
    while ($row = $result->fetch()) {
        $ad = array($row['Title'], $row['description'], $row['Categories'], $row['filename'], $row['biddingprice']);
        $content[] = $ad;
    }
    return $content;
}

$value = getAd();

function getBids()
{
    $url = $_GET['link'];
    global $pdo;
    $result = $pdo->query("select biddingprice as biddingprice, [user], productid from Bidding where productid like '%$url%'");
    $bids = array();
    while ($row = $result->fetch()) {
        $bid = array($row['biddingprice'], $row['user']);
        $bids[] = $bid;
    }
    return $bids;
}

function getPhotos()
{
    $url = $_GET['link'];
    global $pdo;
    $result = $pdo->query("select filename from productPhoto where productid like '%$url%'");
    $photos = array();
    while ($row = $result->fetch()) {
        $photo = array($row['filename']);
        $photos[] = $photo;
    }
    return $photos;
}


?>

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php
                        $getPhotos = getPhotos();
                        foreach ($getPhotos as $i => $value) {
                            ?>
                            <li class="<?php if ($i == 0) {
                                echo "active";
                            } else {
                                echo "";
                            } ?>" data-target="#carourselExampleIndicators" data-slide-to="<?php echo $i ?>"></li>
                            <?php
                        }
                        ?>

                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <?php
                        foreach ($getPhotos as $i => $value) {
                            if (substr($value[0], 0, 2) == 'dt') {
                                $piclocation = "/pic/";
                            } else {
                                $piclocation = "/uploads/";
                            }
                            ?>
                            <div class="carousel-item <?php if ($i == 0) {
                                echo "active";
                            } ?>">
                                <img class="d-block img-fluid"
                                     src="<?= $app_url ?><?= $piclocation ?><?php echo $value[0] ?>">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php if (count(getPhotos()) > 1) {
                        ?>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <?php
                    }
                    ?>

                </div>
                <div class="well">
                    <div class="text-right">
                        <button onclick="showInput()" name="paymentBtn" id="paymentBtn" type="submit"
                                class="btn btn-success">Bied nu!
                        </button>
                    </div>
                </div>
                <form method="post">
                    <div id="showInput"
                         style="display: none" <?php print((!empty($errors['bod'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                        <label class="col-2 col-form-label"></label>
                        <div class="input-inline col-10">

                            <input id="minimum-bid-price" placeholder="€ 0,00" name="bod" type="number"
                                   step="0.01"
                                   class="form-control">
                            <div class="form-control-feedback"><?= $errors['startprice']??'' ?></div>
                            <button class="btn btn-secondary">Plaats bod!</button>
                        </div>
                    </div>
                </form>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star-empty"></span>
                        Anonymous
                        <span class="pull-right">10 days ago</span>
                        <p>This product was great in terms of quality. I would definitely buy another!</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star-empty"></span>
                        Anonymous
                        <span class="pull-right">12 days ago</span>
                        <p>I've alredy ordered another one!</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star-empty"></span>
                        Anonymous
                        <span class="pull-right">15 days ago</span>
                        <p>I've seen some better than this, but not at this price. I definitely recommend this
                            item.</p>
                    </div>
                </div>

            </div>

        </div>
        <ul class="list-group">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Bod</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $Bids = getBids();
                $i = 0;
                foreach ($Bids as $value) {
                    $i++;
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $value[1] . "</td>";
                    echo "<td>" . $value[0] . "</td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </ul>
    </div>
    </div>

    <!-- /.container -->


    <!-- /.container -->

    <!-- jQuery -->
    <script>
        function showInput() {
            var x = 1;
            var y = 0;
            if (x > y) {
                document.getElementById("showInput").style.display = 'block';
                x = -1;
            } else if (x < y) {
                document.getElementById("showInput").style.display = 'none';
                x = 1;
            }
        }
    </script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
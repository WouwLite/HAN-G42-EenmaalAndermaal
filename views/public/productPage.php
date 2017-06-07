<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php');
function getAd()
{
    global $url;
    $url = $_GET['link'];
    global $pdo;
    $result = $pdo->query("select o.productid, Title, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories, pp.filename, (select max(biddingprice)
																																from Bidding b
																																where o.productid = b.productid) as biddingprice
from Object o left outer join productPhoto pp on o.productid=pp.productid
where o.productid = '$url'");
    $content = array();
    while ($row = $result->fetch()) {
        $ad = array($row['Title'], $row['description'], $row['Categories'], $row['filename'], $row['biddingprice']);
        $content[] = $ad;
    }
    return $content;
}

$value = getAd();
function checkBod()
{
    global $errors;
    $errors['bod'] = ($_POST['bod'] == "") && ($_POST['bod'] < selectHighestBid()) ? "Vul aub een geldig bod in" : '';
}

function checkNoErrorBod()
{
    global $errors;
    if (!empty($errors['bod'])) return false;
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $errors;
    if (isset($_POST['submit'])) {
        checkBod();
    }
    if (checkNoErrorBod() && $_POST['bod'] >= selectHighestBid() && $_POST['bod'] > selectStartPrice()) {
        saveBid();
    } else if (!empty(selectHighestBid()) && $_POST['bod'] < selectHighestBid()) {
        $errors['bod'] = "Vul aub een hoger bod in dan het huidige bod";
    } else if (!empty(selectHighestBid()) && $_POST['bod'] < selectStartPrice()) {
        $errors['bod'] = "Vul aub een hoger bod in de startprijs";
    } else if($_POST['bod'] > 9999){
        $errors['bod'] = "Uw account zal nu worden geblokkeerd";
    }
}

function selectStartPrice()
{
    global $url, $pdo;
    $result = $pdo->query("select startprice from Object where productid like '%$url%'");
    $row = $result->fetch();
    return $row['startprice'];
}

function getBids()
{
    global $url;
    global $pdo;
    $result = $pdo->query("select top 5 biddingprice as biddingprice, [user], productid from Bidding where productid like '%$url%' order by biddingprice DESC");
    $bids = array();
    while ($row = $result->fetch()) {
        $bid = array($row['biddingprice'], $row['user']);
        $bids[] = $bid;
    }
    return $bids;
}

function selectHighestBid()
{
    global $url, $pdo;
    $result = $pdo->query("select max(biddingprice) as biddingprice from Bidding where productid = '$url'");
    $row = $result->fetch();
    return $row['biddingprice'];
}

function getPhotos()
{
    global $url;
    global $pdo;
    $result = $pdo->query("select filename from productPhoto where productid like '%$url%'");
    $photos = array();
    while ($row = $result->fetch()) {
        $photo = array($row['filename']);
        $photos[] = $photo;
    }
    return $photos;
}

function saveBid()
{
    global $pdo, $url;
    $stmt = "INSERT INTO Bidding(productid, biddingprice, [user], biddingday, biddingtime)
             VALUES (?, ?, ?, ?, ?)";
    $addBid = $pdo->prepare($stmt);
    if ($addBid->execute([$url, $_POST['bod'], $_SESSION['username'], date("Y-m-d"), date("H:i:s")])) {
        //header("Refresh:0");
    }
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators"">
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
                        ?>
                        <div class="carousel-item <?php if ($i == 0) {
                            echo "active";
                        } ?>">
                            <?php
                            if (substr($value[0], 0, 3) === "dt_") {
                                $picsource = "http://iproject42.icasites.nl/pics/";
                            } else {
                                $picsource = "http://iproject42.icasites.nl/uploads/";
                            }
                            ?>
                            <img class="d-block img-fluid size" src="<?= $picsource ?>/<?php echo $value[0] ?>" style="max-height: 400px; width:100%;">

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

                <div class="img-thumbnail">
                    <div class="figure-caption">
                        <h4 class="pull-right"><?php
                            if (empty(selectHighestBid())) {
                                echo selectStartPrice();
                            } else {
                                echo selectHighestBid();
                            }
                            ?></h4>
                        <h4><?php echo getAd()[0][0] ?></h4>
                        <p><?php echo getAd()[0][1] ?></p>
                    </div>
                </div>
            </div>
            <div class="well">
                <div class="text-right">
                    <button onclick="showInput()" name="paymentBtn" id="paymentBtn"
                            class="btn btn-success">Bied nu!
                    </button>
                </div>
            </div>
            <form method="post">
                <div id="showInput"
                     style="display: none" <?php
                if (!empty($errors['bod'])) {
                    echo "class=\"form-group row has-danger\"";
                } else if (isset($_POST['bod']) && $_POST['bod'] < selectHighestBid()) {
                    echo "class=\"form-group row has-danger\"";
                } else {
                    echo "class=form-group row";
                }
                //  print((!empty($errors['bod'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                    <label class="col-2 col-form-label"></label>
                    <div class="input-inline col-10">

                        <input placeholder="€ 0,00" id="bod" name="bod" type="number"
                               step="0.01"
                               class="form-control">
                        <div class="form-control-feedback"><?php global $errors;
                            echo $errors['bod'];

                            ?></div>

                        <?php if (isset($_SESSION['username'])) {
                            ?>
                            <button name="submit" id="submit" class="btn btn-secondary">Plaats bod!</button>
                            <?php echo selectHighestBid();
                        } else {
                            ?>
                            <h4>Meld u eerst aan om een bod te plaatsen!</h4>
                            <?php
                        } ?>
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


        <div class="list-group">
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
        </div>
    </div>

</div>

<!-- /.container -->


<!-- /.container -->

<!-- jQuery -->


<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>

<script>

    var x = 1;
    var y = 0;
    function showInput() {
        if (x > y) {
            document.getElementById("showInput").style.display = 'block';
            x = -1;
        } else if (x < y) {
            document.getElementById("showInput").style.display = 'none';
            x = 1;
        }
    }
</script>

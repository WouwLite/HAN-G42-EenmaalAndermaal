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
    $result = $pdo->query("select filename from Productphotos where product id like '%$url%'");
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
                <div class="card">
                    <img class="card-img-top img-fluid" src="http://placehold.it/800x300" alt="">
                    <div class="card-block">
                        <h4 class="pull-right"><?php if (!empty(getAd()[0][4])) {
                                echo "€" . getAd()[0][4];
                            } else {
                                echo "Nog geen bod geplaatst";
                            } ?></h4>
                        <h4><?php echo getAd()[0][0] ?>
                        </h4>
                        <p><?php echo getAd()[0][1] ?></p>
                    </div>
                </div>

                <?php
                if (isset($_POST['biednu'])) {
                    echo "oke mooi man";
                }
                ?>

                <div class="well">

                    <div class="text-right">
                        <button onclick="showInput()" name="paymentBtn" id="paymentBtn" type="submit"
                                class="btn btn-success">Bied nu!
                        </button>
                    </div>
                    <div id="showInput" style="display: none" <?php print((!empty($errors['startprice'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                        <label class="col-2 col-form-label">Prijs*</label>
                        <div class="input-inline col-10">

                            <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice" type="number" step="0.01"
                                   class="form-control">
                            <div class="form-control-feedback"><?= $errors['startprice']??'' ?></div>
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
        function showInput(){
            document.getElementById("showInput").style.display = block;
        }
    </script>

<?php
function showInputPayment()
{
    ?>
    <div <?php print((!empty($errors['startprice'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
        <label class="col-2 col-form-label">Prijs*</label>
        <div class="input-inline col-10">

            <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice" type="number" step="0.01"
                   class="form-control">
            <div class="form-control-feedback"><?= $errors['startprice']??'' ?></div>
        </div>

    <?php
}

?>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
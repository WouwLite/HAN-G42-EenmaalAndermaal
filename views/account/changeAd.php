<?php
$hostname = "mssql2.iproject.icasites.nl"; //Naam van de Server
$dbname = "iproject42";    //Naam van de Database
$username = "iproject42";      //Inlognaam
$pw = "7MqNNSxC";      //Password

$pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");

require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT * FROM Object WHERE seller = ? AND productid = ? ");
    $stmt->execute([$user, $_GET['id']]);
    $dataAd = $stmt->fetch(PDO::FETCH_ASSOC);
}


$vars = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $vars, $errors;
    $vars = getRealPOST();
    if (isset($vars['final-submit'])) {
        checkEmptyFields();
        if (checkNoErrors()) {
            updateProductData();
        }
    }
}

function getHighestId()
{
    global $pdo;
    $stmt = $pdo->query("select top 1 MAX(productid) + 1 from Object");
    $data = $stmt->fetchColumn();
    if ($data == 0) {
        $data = 1;
        return $data;
    } else {
        return $data;
    }
}

function getRealPOST()
{
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}

function checkEmptyFields()
{
    global $errors;
    global $vars;
    $errors['title'] = ($vars['title'] == "") ? "Vul aub een titel in voor de advertentie" : '';
    $errors['description'] = ($vars['description'] == "") ? "Vul aub een beschrijving in." : '';
    //$errors['foto'] = ($vars['foto1'] == "") ? "." : '';
    $errors['startprice'] = ($vars['startprice'] == "") ? "Vul aub een prijs in." : '';
    $errors['paymentmethod'] = ($vars['paymentmethod'] == "") ? "Vul aub een betaalmethode in." : '';
    $errors['shippingcosts'] = ($vars['shippingcosts'] == "") ? "Vul aub de verzendkosten in." : '';
    $errors['duration'] = ($vars['duration'] == "") ? "Vul aub de lengte van uw advertentie in." : '';
}

function checkNoErrors()
{
    global $errors;
    foreach ($errors as $err) {
        if (!empty($err)) return false;
    }
    return true;
}

function updateProductData()
{
    global $user, $_SESSION, $vars;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $vars = getRealPost();
        $title = $vars['title'];
        $description = $vars['description'];
        $startprice = $vars['startprice'];
        if ($vars['paymentmethod'] == 'Creditcard') {
            $paymentmethod = 1;
        } else {
            $paymentmethod = 2;
        }
        $productid = $vars['productid'];
        $duration = $vars['duration'];
        $paymentinstruction = $vars['paymentinstruction'];
        $duration = $vars['duration'];
        $durationbeginDay = date("Y-m-d");
        $durationbeginTime = date("h:i:sa");
        $shippingCosts = $vars['shippingcosts']; //vervangen!!!
        $shippingInstructions = "niks"; //vervangen!!!
        $days = $duration;
        $durationendDay = date('Y-m-d', strtotime('+' . $days . 'days'));
        $durationendTime = $durationbeginTime;
        $categorieName = $vars['Categories'];
        global $pdo;
        $stmt = "UPDATE Object
                  SET title = ?, description = ?, startprice = ?, paymentmethodNumber = ?, paymentinstruction = ?,
                      duration = ?, durationbeginDay = ?, durationbeginTime = ?, shippingcosts = ?,
                      shippinginstructions = ?, durationendDay = ?, durationendTime = ?, Categories = ?
                  WHERE productid = ?";

        $updateAdInfo = $pdo->prepare($stmt);
        if ($updateAdInfo->execute(array($title, $description, (float)$startprice, (int)$paymentmethod, $paymentinstruction,
            (int)$duration, $durationbeginDay, $durationbeginTime,
            (float)$shippingCosts, $shippingInstructions, $durationendDay, $durationendTime, (int)$categorieName, (int)$productid))
        ) {
            header('location: ../account/index.php');
        } else {
            print_r($updateAdInfo->errorInfo());
        }


    }
}

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Advertentie aanmaken</title>
    </head>

    <body>

    <div class="container-float">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $app_url ?>">Thuis</a></li>
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Advertentie wijzigen</li>
        </ol>
    </div>
    <div class="col-10">
        <a href="<?= $app_url ?>" class="btn btn-info btn-lg" role="button" aria-pressed="true">Terug</a>
    </div>

    <div class="container main-part">
        <form action="#" method="post">
            <div class="form-group row">
                <label class="col-2 col-form-label"></label>
                <div class="col-8">
                    <h1 class="product-title-page">Wijzig
                        advertentie </h1>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Categorie*</label>
                <div class="col-10">
                    <select class="form-control" id="Categories" name="Categories">
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM Categories");
                        $stmt->execute();
                        $data = $stmt->fetchAll();
                        foreach ($data as $row) { ?>
                            <!--                        <option>--><?php //echo $row['Name']?><!--</option>-->
                            <option <?php print(($dataAd['Categories'] == $row['ID']) ? "selected" : "") ?>
                                    value="<?= $row['ID'] ?>"><?php echo $row['Name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div <?php print((!empty($errors['title'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label for="title" class="col-2 col-form-label">Titel:*</label>
                <div class="col-10">

                    <input id="title" type="text" id="title" name="title" class="form-control" placeholder="Titel:"
                           value="<?php echo $dataAd['title']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['title'] ?></div>
                </div>
            </div>

            <input type="hidden" value="<?php echo $_GET['id']; ?>" name="productid">

            <div <?php print((!empty($errors['description'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Beschrijving:*</label>
                <div class="col-10">
            <textarea class="form-control" rows="4" name="description"
                      placeholder="Plaats hier een beschrijving van uw product"><?php echo $dataAd['description']; ?></textarea>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['description'] ?></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Upload tot 4 fotos:</label>
                <div class="col-10">
                    <input type="file" name="foto1" id="foto2" class="form-control">
                    <input type="file" name="foto2" id="foto1" class="form-control">
                    <input type="file" name="foto3" id="foto3" class="form-control">
                    <input type="file" name="foto4" id="foto4" class="form-control">
                </div>
            </div>
            <div <?php print((!empty($errors['startprice'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Prijs*</label>
                <div class="input-inline col-10">
                    <div class="form-check">
        <span class="inline-input">
        <input onclick="check()" type="radio" id="radio1" class="minimum-bid-price"
               value="<?php echo $dataAd['startprice'];?>"<?php if($vars['startprice'] > 0){
                   echo "checked";
        }?>>
                        <label for="minimum-bid-price">Start bieden vanaf:</label>
                    </div>
                    </span>

                    <div class="form-check">
                <span class="inline-input">
            <input onclick="uncheck()" type="radio" id="radio2" class="minimum-bid-price">
            <label>Geen minimale prijs</label>
                </span>
                    </div>
                    <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice"
                           value="<?php echo $dataAd['startprice']; ?>" type="number" class="form-control"
                           disabled>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['startprice'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['paymentmethod'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Betaal methode:*</label>
                <div class="col-10">
                    <select class="form-control" name="paymentmethod" id="payment-method">
                        <option <?php print(($dataAd['paymentmethodNumber'] == 1) ? "selected" : "") ?>
                                name="Creditcard">Creditcard
                        </option>
                        <option <?php print(($dataAd['paymentmethodNumber'] == 2) ? "selected" : "") ?> name="Bankgiro">
                            Bankgiro
                        </option>
                    </select>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['paymentmethod'] ?></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Verzendkosten:</label>
                <div class="col-10">
                    <input id="minimum-bid-price" placeholder="€ 0,00" name="shippingcosts" type="number" step="0.01"
                           value="<?php echo $dataAd['shippingcosts']; ?>"
                           class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Betaal instructie</label>
                <div class="col-10">
        <textarea class="form-control" rows="4"
                  placeholder="Plaats hier betaal instructie"
                  name="paymentinstruction"><?php echo $dataAd['paymentinstruction']; ?></textarea>
                </div>
                <label class="col-2 col-form-label">Verzend instructie</label>
                <div class="col-10">
            <textarea class="form-control" rows="4"
                      placeholder="Plaats hier uw verzend instructie"
                      name="shippinginstruction"><?php echo $dataAd['shippinginstructions']; ?></textarea>
                </div>
            </div>


            <div <?php print((!empty($errors['duration'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label for="daysforsayle" class="col-2 col-form-label">Aantal dagen biedtijd:*</label>
                <div class="col-10">
                    <select class="form-control" id="duration" name="duration" id="daysforsayle">
                        <option <?php print(($dataAd['duration'] == 1) ? "selected" : "") ?>>1</option>
                        <option <?php print(($dataAd['duration'] == 3) ? "selected" : "") ?>>3</option>
                        <option <?php print(($dataAd['duration'] == 5) ? "selected" : "") ?>>5</option>
                        <option <?php print(($dataAd['duration'] == 7) ? "selected" : "") ?>>7</option>
                    </select>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['duration'] ?></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 col-form-label"></label>
                <div class="form-check col-10">
                    <input type="checkbox" required>
                    Ik ga akkoord met de algemene voorwaarden
                </div>
            </div>
            <div class="form-group row">
                <div class="col-10">
                    <button class="btn btn-success btn-lg" type="submit" name="final-submit">Doorgaan</button>
                </div>
            </div>
        </form>
    </div>


    </body>


    </html>

    <script>
        function check() {
            document.getElementById("minimum-bid-price").disabled = false;
            document.getElementById("radio2").checked = false;
        }
        function uncheck() {
            document.getElementById("minimum-bid-price").disabled = true;
            document.getElementById("radio1").checked = false;
            document.getElementById("minimum-bid-price").value = "€ 0,00";
        }
    </script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');


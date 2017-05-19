<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
//session_start();
    include_once ($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

session_start();

$user = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
$stmt->execute([$user]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$vars = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $vars, $errors;
    $vars = getRealPOST();
     if (isset($vars['final-submit'])) {
        checkEmptyFields();
        if(checkNoErrors()){
            saveProductData();
        }
    }
}

function getHighestId()
{
    global $pdo;
    $stmt = $pdo->query("select top 1 MAX(productid) + 1 from Object");
    $data = $stmt->fetchColumn();
    if($data == 0){
        $data = 1;
        return $data;
    }
    else {
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

function saveProductData()
{
    global $user, $_SESSION, $vars;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $vars = getRealPost();
        $productid = getHighestId();
        $title = $vars['title'];
        $description = $vars['description'];
        $startprice = $vars['startprice'];
        if ($vars['paymentmethod'] == 'Creditcard') {
            $paymentmethod = 1;
        } else {
            $paymentmethod = 2;
        }
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
        $foto1 = $vars['foto1'] ?? null;
        $foto2 = $vars['foto2'] ?? null;
        $foto3 = $vars['foto3'] ?? null;
        $foto4 = $vars['foto4'] ?? null;
        $categorieName = $vars['Categories'];
        global $pdo;
        $stmt = "INSERT INTO Object(productid, title, description, startprice, paymentmethodNumber, paymentinstruction,
                  city, country, duration, durationbeginDay, durationbeginTime, shippingcosts, shippinginstructions, seller,
                  durationendDay, durationendTime, auctionclosed, Categories)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)";

        $stmt2 = "INSERT INTO productphoto(productid, filename)
                 VALUES (?, ?)";
        $adInfo = $pdo->prepare($stmt);
        $photoInfo = $pdo->prepare($stmt2);
        if ($foto1) $photoInfo->execute(array($productid, $foto1));
        if ($foto2) $photoInfo->execute(array($productid, $foto2));
        if ($foto3) $photoInfo->execute(array($productid, $foto3));
        if ($foto4) $photoInfo->execute(array($productid, $foto4));

        $adInfo->execute(array($productid, $title, $description, (float)$startprice, (int)$paymentmethod, $paymentinstruction,
            $_SESSION['city'], $_SESSION['country'], (int)$duration, $durationbeginDay, $durationbeginTime,
            (float)$shippingCosts, $shippingInstructions, $_SESSION['username'], $durationendDay, $durationendTime, (int)$categorieName));
        header('location: ../account/index.php');
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
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Advertentie aanmaken</li>
    </ol>
</div>
    <div class="col-10">
        <a href="<?=$app_url?>" class="btn btn-info btn-lg" role="button" aria-pressed="true">Terug</a>
    </div>

<?php if($data['merchant'] == 1){ ?>
<div class="container main-part">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <label class="col-2 col-form-label"></label>
            <div class="col-8">
                <h1 class="product-title-page">Plaats advertentie</h1>
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
                        <option value="<?=$row['ID']?>"><?php echo $row['Name']?></option>
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
                       autofocus>
                <div class="form-control-feedback"><?php global $errors;
                    echo $errors['title'] ?></div>
            </div>
        </div>

        <div <?php print((!empty($errors['description'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <label class="col-2 col-form-label">Beschrijving:*</label>
            <div class="col-10">
            <textarea class="form-control" rows="4" name="description"
                      placeholder="Plaats hier een beschrijving van uw product"></textarea>
            <div class="form-control-feedback"><?php global $errors;
                    echo $errors['description'] ?></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Upload tot 4 fotos:</label>
            <div class="col-10">
                <input type="file" name="picture" id="picture" class="form-control">
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
                <input onclick="check()" type="radio" id="radio1" class="minimum-bid-price">
                                <label for="minimum-bid-price">Start bieden vanaf:</label>
                </div>
                </span>

                <div class="form-check">
                        <span class="inline-input">
                    <input onclick="uncheck()" type="radio" id="123" class="minimum-bid-price">
                    <label>Geen minimale prijs</label>
                        </span>
                </div>
                <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice" type="number" step="0.01" class="form-control"
                       disabled>
                <div class="form-control-feedback"><?php global $errors;
                    echo $errors['startprice'] ?></div>
            </div>
        </div>

        <div <?php print((!empty($errors['paymentmethod'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <label class="col-2 col-form-label">Betaal methode:*</label>
            <div class="col-10">
                <select class="form-control" name="paymentmethod" id="payment-method">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM paymentmethods");
                    $stmt->execute();
                    $dataPaymentMethods = $stmt->fetchAll();
                    foreach ($dataPaymentMethods as $row) { ?>
                        <option><?php echo $row['paymentmethod'] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <div class="form-control-feedback"><?php global $errors;
                    echo $errors['paymentmethod'] ?></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Verzendkosten:</label>
            <div class="col-10">
                <input id="minimum-bid-price" placeholder="€ 0,00" name="shippingcosts" type="number" step="0.01"
                       class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Betaal instructie</label>
            <div class="col-10">
                <textarea class="form-control" rows="4"
                      placeholder="Plaats hier betaal instructie" name="paymentinstruction"></textarea>
            </div>
            <label class="col-2 col-form-label">Verzend instructie</label>
            <div class="col-10">
                    <textarea class="form-control" rows="4"
                      placeholder="Plaats hier uw verzend instructie" name="shippinginstruction"></textarea>
            </div>
        </div>


        <div <?php print((!empty($errors['duration'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <label for="daysforsayle" class="col-2 col-form-label">Aantal dagen biedtijd:*</label>
            <div class="col-10">
                <select class="form-control" id="duration" name="duration" id="daysforsayle">
                    <option>1</option>
                    <option>3</option>
                    <option>5</option>
                    <option>7</option>
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

<?php }

else {
    echo 'U moet eerst een verkoper zijn';
}

?>


</body>

<script>
    function check() {
        document.getElementById("minimum-bid-price").disabled = false;
        document.getElementById("123").checked = false;
    }
    function uncheck() {
        document.getElementById("minimum-bid-price").disabled = true;
        document.getElementById("radio1").checked = false;
        document.getElementById("minimum-bid-price").value = "€ 0,00";
    }
</script>

</html>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');

?>


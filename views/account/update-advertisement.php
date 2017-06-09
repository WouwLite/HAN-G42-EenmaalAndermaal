<!-- /views/account/update-advertisement.php -->

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT * FROM Object WHERE productid = ? ");
    $stmt->execute([($_POST['changeid'] ?? $_POST['productid'])]);
    $dataAd = $stmt->fetch(PDO::FETCH_ASSOC);
}

$vars = array();
if ($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['changeid'])) {
    global $errors;
    if (isset($_POST['final-submit'])) {
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

function checkEmptyFields()
{
    global $errors;
    $errors['title'] = ($_POST['title'] == "") ? "Vul aub een titel in voor de advertentie" : '';
    $errors['description'] = ($_POST['description'] == "") ? "Vul aub een beschrijving in." : '';
    //$errors['foto'] = ($vars['foto1'] == "") ? "." : '';
    if ($_POST['startprice'] = 1) {
        $errors['startprice'] = ($_POST['startprice'] == "") ? "Vul aub een prijs in." : '';
    }
    $errors['paymentmethod'] = ($_POST['paymentmethod'] == "") ? "Vul aub een betaalmethode in." : '';
    $errors['duration'] = ($_POST['duration'] == "") ? "Vul aub de lengte van uw advertentie in." : '';
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
    global $_SESSION, $pdo, $changeProduct;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $duration = $_POST['duration'];
        $durationbeginDay = date("Y-m-d");
        $durationbeginTime = date("h:i:sa");
        $days = $duration;
        $durationendDay = date('Y-m-d', strtotime('+' . $days . 'days'));
        $durationendTime = $durationbeginTime;
        $stmt = "UPDATE Object
                  SET title = ?, description = ?, startprice = ?, paymentmethodNumber = ?, paymentinstruction = ?,
                      duration = ?, durationbeginDay = ?, durationbeginTime = ?, shippingcosts = ?,
                      shippinginstructions = ?, durationendDay = ?, durationendTime = ?, Categories = ?
                  WHERE productid = ?";
        $changeProduct = $_POST['productid'] ?? $_POST['changeid'];
        $updateAdInfo = $pdo->prepare($stmt);
        if ($updateAdInfo->execute(array($_POST['title'], $_POST['description'], $_POST['startprice']??0, $_POST['paymentmethod'], $_POST['paymentinstruction'],
            (int)$duration, $durationbeginDay, $durationbeginTime,
            (int)$_POST['shippingcosts']??0, $_POST['shippinginstruction'], $durationendDay, $durationendTime, (int)$_POST['categories'], $changeProduct))
        ) {
            //header('location: ../account/index.php');
        } else {

        }

//
    }
}

$stmt = $pdo->prepare("SELECT auctionClosed FROM Object WHERE productid = ?");
$stmt->execute([($_POST['changeid'] ?? $_POST['productid'])]);
$dataAdClosed = $stmt->fetchColumn();

if (empty($_SESSION['username'])) {
    include($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
}
$date1 = date("Y-m-d H:i:s");
$date2 = $dataAd['durationendDay'] . ' ' . $dataAd['durationendTime'];
?>



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
<form action="#" method="post">
<div class="container main part">
    <div action="#" method="post">
        <div class="form-group row">
            <label class="col-2 col-form-label"></label>
            <div class="col-8">
                <h1 class="product-title-page">Wijzig advertentie</h1>
            </div>
        </div>
    </div>

    <?php
    if ($dataAdClosed == 1){
        print '<div class="alert alert-danger"><strong>Oei! </strong> Het lijkt erop dat deze advertentie als is verlopen</div></div>';
    }
    else if (strtotime($date1) <= strtotime($date2)) {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and !isset($_POST['changeid']) and checkNoErrors()) {
            print("<div class='alert alert-success'><strong>Gelukt<br></strong> Uw advertentie is succesvol bijgewerkt.</div>");
    } else if ($_SERVER['REQUEST_METHOD'] == "POST" and !isset($_POST['changeid']) and !checkNoErrors()) {
        print("<div class='alert alert-danger'><strong>Oei!</strong> Er ging iets mis tijdens het bijwerken van de advertentie,
                                    controleer en pas de rode velden aan en probeer het daarna opnieuw</div>");
    }
    ?>
</div>
<div class="form-group row">
    <label class="col-2 col-form-label">Categorie*</label>
    <div class="col-10">
        <select class="form-control" id="categories" name="categories">
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

        <input id="title" type="text" id="title" name="title" class="form-control" placeholder="Titel"
               value="<?php echo $dataAd['title']; ?>"
               autofocus>
        <div class="form-control-feedback"><?php global $errors;
            echo $errors['title'] ?></div>
    </div>
</div>

<input type="hidden" value="<?php echo $_POST['changeid']??$_POST['productid']; ?>" name="productid"
       id="productid">

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
        <?php
        $stmt = $pdo->prepare("SELECT filename FROM productPhoto WHERE productid = ?");
        $stmt->execute([$dataAd['productid']]);
        $dataPictures = $stmt->fetchAll();
        ?>
        <input type="file" name="foto1" id="foto2" class="form-control" value="HOIDITISEENTEST">
        <input type="file" name="foto2" id="foto1" class="form-control" value="<?= $dataPictures ?>">
        <input type="file" name="foto3" id="foto3" class="form-control" value="<?= $dataPictures ?>">
        <input type="file" name="foto4" id="foto4" class="form-control" value="<?= $dataPictures ?>">
    </div>
</div>
<div <?php print((!empty($errors['startprice'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
    <label class="col-2 col-form-label">Prijs*</label>
    <div class="input-inline col-10">
        <label class="custom-control custom-radio">
            <input onclick="uncheck()" id="radio2" name="radio" type="radio" class="custom-control-input"
                   value="2">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Geen minimale prijs</span>
        </label>
        <label class="custom-control custom-radio">
            <input onclick="check()" id="radio2" name="radio" type="radio" class="custom-control-input"
                   value="1">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Start bieden vanaf:</span>
        </label>
        <input id="minimum-bid-price" placeholder="€ 0.00" name="startprice"
               value="<?php echo $dataAd['startprice']; ?>" type="number" step="0.01" class="form-control"
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
                <option value="<?= $row['paymentmethodNumber'] ?>" <?= ($dataAd['paymentmethodNumber'] == $row['paymentmethodNumber']) ? 'selected' : '' ?>><?php echo $row['paymentmethod'] ?></option>
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

<div class="form-group row form-check col-10">
    <label class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" required>
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">Ik ga akkoord met de algemene voorwaarden</span>
    </label>
</div>
<div class="form-group row">
    <div class="col-10">
        <button class="btn btn-success btn-lg" type="submit" name="final-submit">Doorgaan</button>
    </div>
</div>
</form>



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



<?php
}

else {
    print("<div class='alert alert-danger'><strong>Oei!</strong> Het lijkt erop dat deze advertentie al is verlopen</div>");
    header("Refresh: 2; url=index.php");
}
?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>
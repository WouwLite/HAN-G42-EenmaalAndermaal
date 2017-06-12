<?php

session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

//session_start();

$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
$stmt->execute([$username]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $_POST, $errors;
//    $_POST = getRealPOST();
    if (isset($_POST['final-submit'])) {
        checkEmptyFields();
        if (checkNoErrors()) {
            saveProductData();
        }
    }
}

if (empty($_SESSION['username'])) {
    include($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
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

//function getRealPOST()
//{
//    $pairs = explode("&", file_get_contents("php://input"));
//    $_POST = array();
//    foreach ($pairs as $pair) {
//        $nv = explode("=", $pair);
//        $name = urldecode($nv[0]);
//        $value = urldecode($nv[1]);
//        $_POST[$name] = $value;
//    }
//    return $_POST;
//}

function checkEmptyFields()
{
    global $errors;
    global $_POST, $pdo;
    $errors['title'] = ($_POST['title'] == "") ? "Vul aub een titel in voor de advertentie" : '';
    $errors['description'] = ($_POST['description'] == "") ? "Vul aub een beschrijving in." : '';
    $errors['Categories'] = ($_POST['Categories'] == "") ? "Vul een categorie in.": '';
    $query = "select ID From [bottom level Categories]";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $children = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if(!in_array($_POST['Categories'], $children)){
        $errors['Categories'] = "Vul aub een geldige categorie in.";
    }


    //$errors['foto'] = ($_POST['foto1'] == "") ? "." : '';
    if ($_POST['radio'] == 1) {
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

function saveProductData()
{
    global $user, $pdo, $app_url, $errors;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productid = getHighestId();
        $duration = $_POST['duration'];
        $durationbeginDay = date("Y-m-d");
        $durationbeginTime = date("h:i:s");
        $days = $duration;
        $durationendDay = date('Y-m-d', strtotime('+' . $days . 'days'));
        $durationendTime = $durationbeginTime;
        $foto1 = ($_FILES['foto1']['error'] == UPLOAD_ERR_NO_FILE) ? null : $_FILES['foto1'];
        $foto2 = ($_FILES['foto2']['error'] == UPLOAD_ERR_NO_FILE) ? null : $_FILES['foto2'];
        $foto3 = ($_FILES['foto3']['error'] == UPLOAD_ERR_NO_FILE) ? null : $_FILES['foto3'];
        $foto4 = ($_FILES['foto4']['error'] == UPLOAD_ERR_NO_FILE) ? null : $_FILES['foto4'];

        $stmt = "INSERT INTO Object(productid, title, description, startprice, paymentmethodNumber, paymentinstruction,
                  city, country, duration, durationbeginDay, durationbeginTime, shippingcosts, shippinginstructions, seller,
                  durationendDay, durationendTime, auctionclosed, Categories)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)";

        $stmt2 = "INSERT INTO productphoto(productid, filename)
                 VALUES (?, ?)";
        $adInfo = $pdo->prepare($stmt);
        $photoInfo = $pdo->prepare($stmt2);
        define('SITE_ROOT', realpath(dirname(__FILE__, 3)));
        $destdir = SITE_ROOT . "\\uploads\\";
        $allowedtypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);

        if (!$foto1 && !$foto2 && !$foto3 && !$foto4) {
            $uniquefilename = uniqid('EAImg') . "." . "png";
            $photoInfo->execute(array($productid, $uniquefilename));
            copy($destdir."geen-afbeelding-beschikbaar.png", $destdir.$uniquefilename);
        }
        if ($foto1) {
            if (in_array(exif_imagetype($foto1['tmp_name']), $allowedtypes)) {
                $ext = pathinfo($foto1['name'], PATHINFO_EXTENSION);
                $uniquefilename = uniqid('EAImg') . "." . $ext;
                $photoInfo->execute(array($productid, $uniquefilename));
                move_uploaded_file($foto1['tmp_name'], $destdir . $uniquefilename);
            } else {
                $errors['files'] = "Je mag alleen bestanden met de bestandtype: .gif, .png, .jpg/.jpeg en .bmp uploaden";
            }
        }
        if ($foto2) {
            if (in_array(exif_imagetype($foto2['tmp_name']), $allowedtypes)) {
                $ext = pathinfo($foto2['name'], PATHINFO_EXTENSION);
                $uniquefilename = uniqid('EAImg') . "." . $ext;
                $photoInfo->execute(array($productid, $uniquefilename));
                move_uploaded_file($foto2['tmp_name'], $destdir . $uniquefilename);
            } else {
                $errors['files'] = "Je mag alleen bestanden met de bestandtype: .gif, .png, .jpg/.jpeg en .bmp uploaden";
            }
        }
        if ($foto3) {
            if (in_array(exif_imagetype($foto3['tmp_name']), $allowedtypes)) {
                $ext = pathinfo($foto3['name'], PATHINFO_EXTENSION);
                $uniquefilename = uniqid('EAImg') . "." . $ext;
                $photoInfo->execute(array($productid, $uniquefilename));
                move_uploaded_file($foto3['tmp_name'], $destdir . $uniquefilename);
            } else {
                $errors['files'] = "Je mag alleen bestanden met de bestandtype: .gif, .png, .jpg/.jpeg en .bmp uploaden";
            }
        }
        if ($foto4) {
            if (in_array(exif_imagetype($foto4['tmp_name']), $allowedtypes)) {
                $ext = pathinfo($foto4['name'], PATHINFO_EXTENSION);
                $uniquefilename = uniqid('EAImg') . "." . $ext;
                $photoInfo->execute(array($productid, $uniquefilename));
                move_uploaded_file($foto4['tmp_name'], $destdir . $uniquefilename);
            } else {
                $errors['files'] = "Je mag alleen bestanden met de bestandtype: .gif, .png, .jpg/.jpeg en .bmp uploaden";
            }
        }
        if (checkNoErrors()) {
            if ($adInfo->execute(array($productid, $_POST['title'], $_POST['description'], $_POST['startprice']??0, (int)$_POST['paymentmethod'], $_POST['paymentinstruction'],
                $user['city'], $user['country'], (int)$duration, $durationbeginDay, $durationbeginTime,
                (int)$_POST['shippingcosts']??0, $_POST['shippinginstruction'], $_SESSION['username'], $durationendDay, $durationendTime, (int)$_POST['Categories']))
            ) {
                //header('location: ../account/index.php');
            } else {
                print_r($adInfo->errorInfo());
            }
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
        <li class="breadcrumb-item active">Advertentie aanmaken</li>
    </ol>
</div>
<div class="col-10">
    <a href="<?= $app_url ?>" class="btn btn-info btn-lg" role="button" aria-pressed="true">Terug</a>
</div>

<?php if ($data['merchant'] == 1) { ?>
    <div class="container main-part">
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="col-2 col-form-label"></label>
                <div class="col-8">
                    <h1 class="product-title-page">Plaats advertentie</h1>
                </div>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" and checkNoErrors()) {
                print("<div class='alert alert-success'><strong>Gelukt<br></strong> Uw advertentie is succesvol geplaatst.</div>");
            } else if ($_SERVER['REQUEST_METHOD'] == "POST" and !checkNoErrors()) {
                print("<div class='alert alert-danger'><strong>Oei!</strong> Er ging iets mis tijdens het plaatsen van de advertentie, 
                        controleer en pas de rode velden aan en probeer het daarna opnieuw</div>");
            }
            ?>
            <div <?php print((!empty($errors['Categories'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Categorie*</label>
                <div class="col-10">
                    <?php
                    $getchild = <<<SQL
WITH CTE (ID, Parent, Name) AS (
    SELECT ID, Parent, Name
    FROM Categories
    WHERE Parent = -1
    UNION ALL
    SELECT parent.ID, parent.Parent, parent.Name
    FROM Categories parent
    INNER JOIN CTE child ON parent.Parent = child.ID
)
SELECT *
FROM CTE
WHERE NOT EXISTS(SELECT * from Categories WHERE Parent = CTE.ID)
SQL;
                    $stmt = $pdo->prepare($getchild);
                    $stmt->execute();
                    $children = $stmt->fetchAll();
                    ?>
                    <input list="categories" class="form-control" name="Categories">
                    <div class="form-control-feedback"><?= $errors['Categories']??'' ?></div>
                    <datalist id="categories">
                            <?php
                            foreach ($children as $child) {
                                echo <<<HTML
                    <option value='{$child['ID']}'>{$child['Name']}</option>
HTML;
                            }
                            ?>

                    </datalist>
                </div>
            </div>
            <div <?php print((!empty($errors['title'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label for="title" class="col-2 col-form-label">Titel:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="title" name="title" class="form-control" placeholder="Titel"
                           autofocus>
                    <div class="form-control-feedback"><?= $errors['title']??'' ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['description'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Beschrijving:*</label>
                <div class="col-10">
            <textarea class="form-control" rows="4" name="description"
                      placeholder="Plaats hier een beschrijving van uw product"></textarea>
                    <div class="form-control-feedback"><?= $errors['description']??'' ?></div>
                </div>
            </div>
            <div <?php print((!empty($errors['files'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Upload tot 4 fotos:</label>
                <div class="col-10">
                    <input type="file" name="foto1" id="foto1" class="form-control">
                    <input type="file" name="foto2" id="foto1" class="form-control">
                    <input type="file" name="foto3" id="foto3" class="form-control">
                    <input type="file" name="foto4" id="foto4" class="form-control">
                    <div class="form-control-feedback"><?= $errors['files']??'' ?></div>
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


                    <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice" type="number" step="0.01"
                           class="form-control"
                           disabled>
                    <div class="form-control-feedback"><?= $errors['startprice']??'' ?></div>
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
                            <option value="<?= $row['paymentmethodNumber'] ?>"><?php echo $row['paymentmethod'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <div class="form-control-feedback"><?= $errors['paymentmethod']??'' ?></div>
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
                    <div class="form-control-feedback"><?= $errors['duration']??'' ?></div>
                </div>
            </div>
            <div class="form-group row form-check col-10">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" required>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Ik ga akkoord met de <a href="../business/terms.php">algemene voorwaarden</a></span>
                </label>
            </div>
            <div class="form-group row">
                <div class="col-10">
                    <button class="btn btn-success btn-lg" type="submit" name="final-submit">Doorgaan</button>
                </div>
            </div>
        </form>
        <br>
        <br>
    </div>

<?php } else {
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
?>


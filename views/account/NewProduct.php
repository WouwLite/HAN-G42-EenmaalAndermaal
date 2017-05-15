<?php
$hostname = "mssql2.iproject.icasites.nl"; //Naam van de Server
$dbname = "iproject42";    //Naam van de Database
$username = "iproject42";      //Inlognaam
$pw = "7MqNNSxC";      //Password

$pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");
saveProductData();
function getHighestId()
{
    global $pdo;
    $stmt = $pdo->query("select top 1 MAX(productid) + 1 from Object");
    $data = $stmt->fetchColumn();
    return $data;
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
    global $vars;

    $title = $vars['title'] ?? "";
    $description = $vars['description'] ?? "";
    $foto1 = $vars['foto1'] ?? "";
    $foto2 = $vars['foto2'] ?? "";
    $foto3 = $vars['foto3'] ?? "";
    $foto4 = $vars['foto4'] ?? "";
    $duration = $vars['duration'] ?? "";
    $startprice = $vars['startprice'] ?? "";
    $paymentmethod = $vars['paymentmethod'];
    $duration = $vars['duration'] ?? "";
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
        if ($vars['paymentmethod'] == 'Pin') {
            $paymentmethod = 1;
        } else {
            $paymentmethod = 2;
        }
        $city = "nijmegen"; //vervangen!!!
        $duration = $vars['duration'];
        $country = "nederland";
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
        $categorieName = $vars['categorie'];
        global $pdo;
        $stmt = "INSERT INTO Object(productid, title, description, startprice, paymentmethodNumber, paymentinstruction, 
              city, country, duration, durationbeginDay, durationbeginTime, shippingcosts, shippinginstructions, seller,
              durationendDay, durationendTime, auctionclosed, Categories)
    VALUES (?, ?, ?, ?, ?, ?, 'Nijmegen', 'nederland', ?, ?, ?, ?, ?, 'JE MOEDER', ?, ?, 1,?)";
//        $stmt2 = "INSERT INTO productphoto(productid, filename)
//    VALUES (?, ?)";
        $processRegistration = $pdo->prepare($stmt);
        //$processRegistration2 = $pdo->prepare($stmt2);
//        if($foto1)$processRegistration2->execute(array($productid, $foto1));
//        if($foto2)$processRegistration2->execute(array($productid, $foto2));
//        if($foto3)$processRegistration2->execute(array($productid, $foto3));
//        if($foto4)$processRegistration2->execute(array($productid, $foto4));
        $processRegistration->execute(array($productid, $title, $description, (float)$startprice, (int)$paymentmethod, (int)$paymentinstruction,
            (int)$duration, $durationbeginDay, $durationbeginTime, (float)$shippingCosts, $shippingInstructions, $durationendDay, $durationendTime, $categorieName));


    }
}
function getCategories(){
    global $pdo;
    $stmt = $pdo->query("select Name, ID From Categories");
    $Categories = array();
    $data = $stmt->fetchAll();
    foreach($data as $row){
        $Categories[$row['ID']] = $row['Name'];
    }
    return $Categories;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advertentie plaatsen</title>
    <link rel="stylesheet" href="assets/css/background.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
            integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
            integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
            crossorigin="anonymous"></script>
</head>
<body class="body">

<div class="container main-part">
    <form action="#" method="post">
        <div class="form-group row">
            <label class="col-2 col-form-label"></label>
            <div class="col-8">
                <h1 class="product-title-page">Plaats advertentie</h1>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Categorie</label>
            <div class="col-10">
                <select name="categorie">
                    <?php
                    $Categorie = getCategories();
                    foreach($Categorie as $key => $value) {
                        echo "<option value=$key>".$value."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="title" class="col-2 col-form-label">Titel</label>
            <div class="col-10">
                <input id="title" type="text" id="title" name="title" class="form-control" placeholder="Titel:"
                       autofocus required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Beschrijving:*</label>
            <div class="col-10">
            <textarea class="form-control" rows="4" name="description"
                      placeholder="Plaats hier een beschrijving van uw product"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Upload tot 4 fotos:</label>
            <div class="col-10">
                <input type="file" name="foto1" name=id="foto1" class="form-control">
                <input type="file" name="foto2" name=id="foto2" class="form-control">
                <input type="file" name="foto3" name=id="foto3" class="form-control">
                <input type="file" name="foto4" name=id="foto4" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Prijs</label>
            <div class="input-inline col-10">
                <div class="form-check">
                <span class="inline-input">
                <input onclick="check()" type="radio" id="radio1" class="minimum-bid-price">
                                <label for="minimum-bid-price">Start bieden vanaf:</label>
                </span>
                </div>
                <div class="form-check">
                    <span class="inline-input">
                <input onclick="uncheck()" type="radio" id="123" class="minimum-bid-price">
            <label>Geen minimale prijs</label>
                    </span>
                </div>
                <input id="minimum-bid-price" placeholder="€ 0,00" name="startprice" type="number" class="form-control"
                       disabled>
            </div>

        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Betaal methode</label>
            <div class="col-10">
                <select class="form-control" name="paymentmethod" id="payment-method">
                    <option name="pin">Pin</option>
                    <option name="creditcard">Creditcard</option>
                </select>
            </div>
        </div>
        <div class="form group row">
            <label class="col-2 col-form-label">Verzendkosten:</label>
            <div class="col-10">
                <input id="minimum-bid-price" placeholder="€ 0,00" name="shippingcosts" type="number"
                       class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Betaal instructie</label>
            <div class="col-10">
            <textarea class="form-control" rows="4"
                      placeholder="Plaats hier betaal instructie" name="paymentinstruction"></textarea>
            </div>
            <label class="col-2 col-form-label">Verzend instructies</label>
            <div class="col-10">
            <textarea class="form-control" rows="4"
                      placeholder="Plaats hier uw verzend instructie" name="shippinginstruction"></textarea>
            </div>

        </div>
        <div class="form-group row">
            <label for="daysforsayle" class="col-2 col-form-label">Aantal dagen biedtijd:</label>
            <div class="col-10">
                <select class="form-control" id="duration" name="duration" id="daysforsayle">
                    <option>1</option>
                    <option>3</option>
                    <option>5</option>
                    <option>7</option>
                </select>
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
            <div class="col-10 ">
                <button class="btn btn-primary" type="submit">Doorgaan</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
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
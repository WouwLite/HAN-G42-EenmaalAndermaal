<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');



if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $dataUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

$_POST = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $_POST, $errors;
    $_POST = getRealPOST();
    if (isset($_POST['final-submit'])) {
        checkEmptyFields();
        checkDuplicates();
        checkAndHashPasswords();
        if (checkNoErrors()) {
            updateUserData();
        }
    }
}

$username       = $_POST['username'] ?? "";
$firstname      = $_POST['firstname'] ?? "";
$lastname       = $_POST['lastname'] ?? "";
$address1       = $_POST['address1'] ?? "";
$address2       = $_POST['address2'] ?? "";
$zipcode     = $_POST['zipcode'] ?? "";
$city       = $_POST['city'] ?? "";
$country       = $_POST['country'] ?? "";
$birthday        = $_POST['birthday'] ?? "";
$email           = $_POST['email'] ?? "";
$password        = $_POST['password'] ?? "";
$password2       = $_POST['password2'] ?? "";
$questionNumber       = $_POST['questionnumber'] ?? "";
$answer   = $_POST['answer'] ?? "";

function checkEmptyFields()
{
    global $errors, $_POST;
    $errors['username'] = ($_POST['username'] == "") ? "Vul aub een gebruikersnaam in." : '';
    $errors['firstname'] = ($_POST['firstname'] == "") ? "Vul aub uw voornaam in." : '';
    $errors['lastname'] = ($_POST['lastname'] == "") ? "Vul aub uw achternaam in." : '';
    $errors['address1'] = ($_POST['address1'] == "") ? "Vul aubuw adres  in." : '';
    $errors['zipcode'] = ($_POST['zipcode'] == "") ? "Vul aub uw postcode in." : '';
    $errors['city'] = ($_POST['city'] == "") ? "Vul aub uw woonplaats in." : '';
    $errors['country'] = ($_POST['country'] == "") ? "Vul aub uw land van herkomst in." : '';
    $errors['birthday'] = ($_POST['birthday'] == "") ? "Vul aub uw geboortedatum in." : '';
    $errors['email'] = ($_POST['email'] == "") ? "Vul aub uw email in." : '';
    $errors['password'] = ($_POST['password'] == "") ? "Vul aub uw wachtwoord in in." : '';
    $errors['password2']    = ($_POST['password2']    == "") ? "vul je wachtwoord nog een keer in aub." : '';
    $errors['answer'] = ($_POST['answer'] == "") ? "Vul aub een antwooord in." : '';
}

function checkDuplicates()
{
    global $user;
    foreach($user as $us){
        if($us['username'] != $_SESSION['username']){
            return true;
        }
        else {
            return false;
        }
    }
}

function checkAndHashPasswords()
{
    global $_POST, $errors;
    $password = $_POST['password1'];
    $password2 = $_POST['password2'];
    if ($password != $password2) {
        $errors['password1'] = "De wachtwoorden moeten gelijk zijn aan elkaar";
        $errors['password2'] = " ";
    } else if (passValid() === true) {
        $_POST['hashedpassword'] = password_hash($password, PASSWORD_DEFAULT);
    }
}

function passValid()
{
    global $_POST, $errors;
    if (preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{5,}$/", $_POST['password'])) {
        return true;
    } else {
        $errors['password'] = 'je wachtwoord moet 5 tekens of langer zijn en er moet minstens 1 hoofdletter en 1 speciale teken in zitten';
        print($_POST['password']);
        return false;
    }
}

function usernameValid()
{
    global $_POST, $errors;
    if (strlen($_POST['username']) >= 3 and strlen($_POST['username']) <= 20) {
        return true;
    } else {
        $errors['username'] = "je gebruikersnaam moet tussen de 3 en 20 tekens lang zijn";
    }
}

function checkNoErrors()
{
    global $errors;
    foreach ($errors as $err) {
        if (!empty($err)) return false;
    }
    return true;
}

function updateUserData()
{
    global $_SESSION, $pdo;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = "UPDATE Users
                  SET username = ?, firstname = ?, lastname = ?, address1 = ?, address2 = ?,
                      zipcode = ?, city = ?, country = ?, birthday = ?,
                      email = ?, password = ?, questionnumber = ?, answer = ?
                  WHERE username = ?";

        $updateUserInfo = $pdo->prepare($stmt);
        $updateUserInfo->execute(array($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['address1'], $_POST['address2'],
            $_POST['zipcode'], $_POST['city'], $_POST['country'], $_POST['birthday'], $_POST['email'], $_POST['password'], $_POST['questionnumber'],
            $_POST['answer']));
        print_r($pdo->errorInfo());

    }
}

if(isset($_SESSION['username'])){
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Account aanpassen</title>
    </head>

    <body>

    <div class="container-float">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $app_url ?>">Thuis</a></li>
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Acount wijzigen</li>
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
                    <h1 class="product-title-page">Wijzig account</h1>
                </div>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" and checkNoErrors() and checkUserExist()) {
                print("<div class='alert alert-success'><strong>Gelukt<br></strong> Uw account is succesvol bijgewerkt.</div>");
            } else if ($_SERVER['REQUEST_METHOD'] == "POST" and !checkNoErrors()) {
                print("<div class='alert alert-danger'><strong>Oei!</strong> Er ging iets mis tijdens het bijwerken van uw account, 
                            controleer en pas de rode velden aan en probeer het daarna opnieuw</div>");
            }
            ?>
            <div <?php print((!empty($errors['username'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label for="title" class="col-2 col-form-label">Gebruikersnaam:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="username" name="username" class="form-control" placeholder="Gebruikersnaam"
                           value="<?php echo $dataUser['username']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['username'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['firstname'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Voornaam:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="firstname" name="firstname" class="form-control" placeholder="Voornaam"
                           value="<?php echo $dataUser['firstname']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['firstname'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['lastname'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Achternaam:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="lastname" name="lastname" class="form-control" placeholder="Achternaam"
                           value="<?php echo $dataUser['lastname']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['lastname'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['address1'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Adres 1:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="address1" name="address1" class="form-control" placeholder="Adres 1"
                           value="<?php echo $dataUser['address1']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['address1'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['address2'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Adres 2:</label>
                <div class="col-10">
                    <input id="title" type="text" id="address2" name="address2" class="form-control" placeholder="Adres 2"
                           value="<?php echo $dataUser['address2']; ?>"
                           autofocus>
                </div>
            </div>

            <div <?php print((!empty($errors['zipcode'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Postcode:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="zipcode" name="zipcode" class="form-control" placeholder="Postcode"
                           value="<?php echo $dataUser['zipcode']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['zipcode'] ?></div>
                </div>
            </div>


            <div <?php print((!empty($errors['city'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Stad:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="city" name="city" class="form-control" placeholder="Stad"
                           value="<?php echo $dataUser['city']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['city'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['country'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <div class="input-group inputform">
                    <label class="col-2 col-form-label">Land:*</label>
                    <select class="form-control" id="country" name="country">
                        <?php
                        $stmtForCountry = $pdo->prepare("SELECT * FROM Country");
                        $stmtForCountry->execute();
                        $dataCountry = $stmtForCountry->fetchAll();
                        echo "<option>Netherlands</option>";
                        foreach ($dataCountry as $row) { ?>
                            <option><?php echo $row['countryname'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <!-- Foutmelding -->
                <div class="form-control-feedback"><?php global $errors;
                    echo $errors['country'] ?>
                </div>
            </div>

            <div <?php print((!empty($errors['birthday'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Geboortedatum:*</label>
                <div class="col-10">
                    <input id="title" type="date" id="birthday" name="birthday" class="form-control" placeholder="Geboortedatum"
                           value="<?php echo $dataUser['birthday']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['birthday'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['email'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Email:*</label>
                <div class="col-10">
                    <input id="title" type="email" id="email" name="email" class="form-control" placeholder="Email"
                           value="<?php echo $dataUser['email']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['email'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['password'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Wachtwoord:*</label>
                <div class="col-10">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Wachtwoord"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['password'] ?></div>
                </div>
            </div>

            <div <?php print((!empty($errors['password2'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Wachtwoord herhalen:*</label>
                <div class="col-10">
                    <input type="password" id="password2" name="password2" class="form-control" placeholder="Wachtwoord herhalen"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['password2'] ?></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="input-group inputform">
                    <label class="col-2 col-form-label">Vraag:*</label>
                    <select class="form-control" id="securityquestion" name="sequrityquestion">
                        <?php
                        $stmtForQuestions = $pdo->prepare("SELECT * FROM Question");
                        $stmtForQuestions->execute();
                        $dataQuestions = $stmtForQuestions->fetchAll();
                        foreach ($dataQuestions as $row) {
                            print("<option value=\"$row[0]\">$row[1]</option>");
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div <?php print((!empty($errors['answer'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                <label class="col-2 col-form-label">Antwoord:*</label>
                <div class="col-10">
                    <input id="title" type="text" id="answer" name="answer" class="form-control" placeholder="Antwoord"
                           value="<?php echo $dataUser['answer']; ?>"
                           autofocus>
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['answer'] ?></div>
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

    <?php
}
else{
    include ($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
}
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>



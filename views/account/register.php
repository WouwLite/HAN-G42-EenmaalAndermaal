<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/app.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/include/style.inc.php");
require($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/getpost.php');
session_start();

$vars = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $vars, $errors;
    $vars = getRealPOST();
    if (isset($vars['email-submit'])) {
        sendmail();
    } elseif (isset($vars['final-submit'])) {
        checkEmptyFields();
        checkDuplicates();
        checkAndHashPasswords();
        checksecretCode();
        if (checkNoErrors()) {
            saveData();
        }
    }
}

$username = $vars['username'] ?? "";
$firstname = $vars['firstname'] ?? "";
$lastname = $vars['lastname'] ?? "";
$email = $vars['email'] ?? "";
$secretcode = $vars['secretcode'] ?? "";
$address1 = $vars['address1'] ?? "";
$address2 = $vars['address2'] ?? "";
$zipcode = $vars['zipcode'] ?? "";
$city = $vars['city'] ?? "";
$country = $vars['country'] ?? "";
$birthday = $vars['birthday'] ?? "";
$secretanswer = $vars['secretanswer'] ?? "";

function checkEmptyFields()
{
    global $errors;
    global $vars;
    $errors['username'] = ($vars['username'] == "") ? "vul je gebruikersnaam in aub." : '';
    $errors['firstname'] = ($vars['firstname'] == "") ? "vul je voornaam in aub." : '';
    $errors['lastname'] = ($vars['lastname'] == "") ? "vul je achternaam in aub." : '';
    $errors['email'] = ($vars['email'] == "") ? "vul je email in aub." : '';
    $errors['secretcode'] = ($vars['secretcode'] == "") ? "vul je geheime code in aub." : '';
    $errors['password1'] = ($vars['password1'] == "") ? "vul je wachtwoord in aub." : '';
    $errors['password2'] = ($vars['password2'] == "") ? "vul je wachtwoord nog een keer in aub." : '';
    $errors['address1'] = ($vars['address1'] == "") ? "vul je adres in aub." : '';
    $errors['zipcode'] = ($vars['zipcode'] == "") ? "vul je postcode in aub." : '';
    $errors['city'] = ($vars['city'] == "") ? "vul je stad in aub." : '';
    $errors['country'] = ($vars['country'] == "") ? "vul je land in aub." : '';
    $errors['birthday'] = ($vars['birthday'] == "") ? "vul je geboorte datum in aub." : '';
    $errors['secretanswer'] = ($vars['secretanswer'] == "") ? "vul je antwoord in aub." : '';
}

function sendmail()
{
    global $vars;
    $secretCode = uniqid();
    $subject = "Eenmaal Andermaal email activatie code";
    $message = "Je geheime code is: " . $secretCode;
    $headers = 'From: noreply@iproject42.icasites.nl';
    mail($vars['email'], $subject, $message, $headers);
    $_SESSION['secretCode'] = password_hash($secretCode, PASSWORD_DEFAULT);
}


function checkDuplicates()
{
    global $errors, $pdo;
    if (isset($vars['username']) and usernameValid()) {
        $username = $vars['username'];
        $stmt = $pdo->prepare("SELECT username FROM Users");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($data as $d) {
            if ($d == $username) {
                $errors['username'] = "Deze gebruikersnaam bestaat al";
                break;
            }
        }
    }

    if (isset($vars['email'])) {
        $email = $vars['email'];
        $stmt = $pdo->prepare("SELECT email FROM Users");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($data as $d) {
            if ($d == $email) {
                $errors['email'] = "Dit email adres bestaat al";
                break;
            }
        }
    }
}

function passValid()
{
    global $vars, $errors;
    if (preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{5,}$/", $vars['password1'])) {
        return true;
    } else {
        $errors['password1'] = 'je wachtwoord moet 5 tekens of langer zijn en er moet minstens 1 hoofdletter en 1 speciale teken in zitten';
        print($vars['password1']);
        print(array_flip(get_defined_constants(true)['pcre'])[preg_last_error()]);
        return false;
    }
}

function usernameValid()
{
    global $vars, $errors;
    if (strlen($vars['username']) >= 3 and strlen($vars['username']) <= 20) {
        return true;
    } else {
        $errors['username'] = "je gebruikersnaam moet tussen de 3 en 20 tekens lang zijn";
    }
}

function checkAndHashPasswords()
{
    global $vars, $errors;
    $password1 = $vars['password1'];
    $password2 = $vars['password2'];
    if ($password1 != $password2) {
        $errors['password1'] = "De wachtwoorden moeten gelijk zijn aan elkaar";
        $errors['password2'] = " ";
    } else if (passValid() === true) {
        $vars['hashedpassword'] = password_hash($password1, PASSWORD_DEFAULT);
    }
}

function checksecretCode()
{
    global $vars, $errors;
    if (password_verify($vars['secretcode'], $_SESSION['secretCode'])) {
        return true;
    } else {
        $errors['secretcode'] = "De code klopt niet, controleer of je de juiste code hebt ingevoerd";
    }
}

function saveData()
{
    global $vars, $pdo;
    $stmt = "INSERT INTO Users (username, firstname, lastname, address1, address2, zipcode, city, country, birthday, email, password, questionnumber, answer, merchant)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,0)";

    $processRegistration = $pdo->prepare($stmt);
    if ($processRegistration->execute([$vars['username'], $vars['firstname'], $vars['lastname'], $vars['address1'],
        $vars['address2'], $vars['zipcode'], $vars['city'], $vars['country'],
        $vars['birthday'], $vars['email'], $vars['hashedpassword'], $vars['sequrityquestion'],
        $vars['secretanswer']])
    ) {
    } else {
        print_r($processRegistration->errorInfo());
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

?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Registratie</title>
    <link rel="stylesheet" href="<?=$app_url?>/assets/css/register.css">

</head>
<body>
<div class="container">
    <form class="form-horizontal sign-up-form" method="post" action="#">
        <div class="title">
            <h1><?=$title?></h1>
        </div>

        <div <?php print((!empty($errors['username'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?> >
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-user"></span>
                <input type="text" id="username" class="form-control" name="username"
                       placeholder="Gebruikersnaam" <?php print("value=\"$GLOBALS[username]\"") ?> autofocus>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['username'] ?></div>
            <small class="form-text text-muted">unieke naam om mee in te loggen (3-20 tekens lang)</small>
        </div>


        <div <?php print((!empty($errors['firstname'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-user" id="basicaddon1"></span>
                <input type="text" id="firstname" class="form-control" name="firstname" placeholder="Voornaam"
                    <?php print("value=\"$GLOBALS[firstname]\"") ?> >
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['firstname'] ?></div>
        </div>


        <div <?php print((!empty($errors['lastname'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-user" id="basicaddon1"></span>
                <input type="text" id="lastname" class="form-control" name="lastname" placeholder="Achternaam"
                    <?php print("value=\"$GLOBALS[lastname]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['lastname'] ?></div>
        </div>


        <div <?php print((!empty($errors['email'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-envelope" id="basicaddon1"></span>
                <input type="email" id="email" class="form-control" name="email" placeholder="E-Mail Adres"
                    <?php print("value=\"$GLOBALS[email]\"") ?> required>
                <span class="input-group-button">
                    <button type="submit" name="email-submit" id="email-submit"
                            class="btn btn-default">Stuur Code</button>
                </span>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['email'] ?></div>

        </div>


        <div <?php print((!empty($errors['secretcode'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-lock" id="basicaddon1"></span>
                <input type="text" id="secretcode" class="form-control" name="secretcode" placeholder="Geheime code"
                    <?php print("value=\"$GLOBALS[secretcode]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['secretcode'] ?></div>
        </div>


        <div <?php print((!empty($errors['password1'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-lock" id="basicaddon1"></span>
                <input type="password" id="password1" class="form-control" name="password1"
                       placeholder="Wachtwoord">
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['password1'] ?></div>
            <small class="form-text text-muted">Een wachtwoord moet minstens 1 hoofdletter, 1 kleine letter, 1 getal
                en 1 speciaal teken bevatten en moet minstens 5 tekens lang zijn
            </small>
        </div>


        <div <?php print((!empty($errors['password2'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-lock" id="basicaddon1"></span>
                <input type="password" id="password2" class="form-control" name="password2"
                       placeholder="Herhaal wachtwoord">
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['password2'] ?></div>
        </div>


        <div <?php print((!empty($errors['address1'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-map-marker" id="basicaddon1"></span>
                <input type="text" id="address1" class="form-control" name="address1" placeholder="Adres"
                    <?php print("value=\"$GLOBALS[address1]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['address1'] ?></div>
        </div>


        <div class="form-group row">
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-map-marker" id="basicaddon1"></span>
                <input type="text" id="address2" class="form-control" name="address2"
                       placeholder="Adres (optioneel)"
                    <?php print("value=\"$GLOBALS[address2]\"") ?>>
            </div>
        </div>


        <div <?php print((!empty($errors['zipcode'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-map-marker" id="basicaddon1"></span>
                <input type="text" id="zipcode" class="form-control" name="zipcode" placeholder="Postcode"
                    <?php print("value=\"$GLOBALS[zipcode]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['zipcode'] ?></div>
        </div>


        <div <?php print((!empty($errors['city'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-map-marker" id="basicaddon1"></span>
                <input type="text" id="city" class="form-control" name="city" placeholder="Plaatsnaam"
                    <?php print("value=\"$GLOBALS[city]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['city'] ?></div>
        </div>


        <div <?php print((!empty($errors['country'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-globe" id="basicaddon1"></span>
                <select class="form-control" id="country" name="country">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM Country");
                    $stmt->execute();
                    $data = $stmt->fetchAll();
                    echo "<option>Netherlands</option>";
                    foreach ($data as $row) { ?>
                        <option><?php echo $row['countryname'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['country'] ?></div>
        </div>


        <div <?php print((!empty($errors['birthday'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-calendar" id="basicaddon1"></span>
                <input type="date" placeholder="Wat is uw geboortedatum" name="birthday" value="<?php if(isset($_POST['birthday'])){ echo $_POST['birthday'];}?>" required>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['birthday'] ?></div>
        </div>

        <div class="form-group row">
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-cog" id="basicaddon1"></span>
                <select class="form-control" id="securityquestion" name="sequrityquestion">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM Question");
                    $stmt->execute();
                    $data = $stmt->fetchAll();
                    foreach ($data as $row) {
                        print("<option value=\"$row[0]\">$row[1]</option>");
                    }
                    ?>
                </select>
            </div>
        </div>


        <div <?php print((!empty($errors['secretanswer'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
            <div class="input-group inputform row">
                <span class="input-group-addon fa fa-cog" id="basicaddon1"></span>
                <input type="text" id="secretanswer" class="form-control" name="secretanswer"
                       placeholder="Antwoord..."
                    <?php print("value=\"$GLOBALS[secretanswer]\"") ?>>
            </div>
            <div class="form-control-feedback"><?php global $errors;
                echo $errors['secretanswer'] ?></div>
        </div>


        <div class="form-group row">
            <div class="col-sm-7 col-sm-offset-6">
                <button type="submit" class="btn btn-primary" id="final-submit" name="final-submit"
                        value="finished">Registreer
                </button>
                <a href="<?=$app_url?>/views/account/login.php" class="btn btn-success" role="button" aria-pressed="true">Aanmelden</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>
<!-- /views/account/index.php -->

<?php


$hostname = "mssql2.iproject.icasites.nl"; //Naam van de Server
$dbname = "iproject42";    //Naam van de Database
$username = "iproject42";      //Inlognaam
$pw = "7MqNNSxC";      //Password

$pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0","$username","$pw");

require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

$merchantStatus = false;

if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$user]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['firstname'] = $data['firstname'];
    $_SESSION['lastname'] = $data['lastname'];
    $_SESSION['address1'] = $data['address1'];
    $_SESSION['zipcode'] = $data['zipcode'];
    $_SESSION['city'] = $data['city'];
    $_SESSION['country'] = $data['country'];
    $_SESSION['birthday'] = $data['birthday'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['questionnumber'] = $data['questionnumber'];
    $_SESSION['answer'] = $data['answer'];
    $_SESSION['merchant'] = $data['merchant'];

}


/*
 * Einde PHP variable-area
 */

if (isset($_SESSION['username'])) {
    ?>
    <div class="container-float">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $app_url ?>">Thuis</a></li>
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Mijn Account</li>
        </ol>
    </div>

    <div class="container-float"><h1>Mijn account</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Gegevens</h4>
                        <p class="card-text">
                        <div class="row">
                            <div class="col-md-6">
                                Gebruikersnaam: <br>
                                Voornaam: <br>
                                Achternaam: <br>
                                Email adres: <br>
                                Geboortedatum: <br>
                            </div>
                            <div class="col-md-6">
                                <?php echo $_SESSION['username']; ?> <br> <?php
                                echo $_SESSION['firstname']; ?> <br> <?php
                                echo $_SESSION['lastname']; ?> <br> <?php
                                echo $_SESSION['email']; ?> <br> <?php
                                echo $_SESSION['birthday']; ?> <br> <?php
                                ?>
                            </div>


                        </div>
                        </p>
                        <a href="<?= $app_url ?>/views/account/edit" class="btn btn-default"><i class="fa fa-wrench"
                                                                                                aria-hidden="true"></i>
                            Gegevens wijzigen</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Adres</h4>
                        <p class="card-text">
                        <div class="row">
                            <div class="col-md-6">
                                Straat: <br>
                                Postcode: <br>
                                Plaats: <br>
                            </div>
                            <div class="col-md-6">
                                <?php echo $_SESSION['address1']; ?> <br> <?php
                                echo $_SESSION['zipcode']; ?> <br> <?php
                                echo $_SESSION['city']; ?> <br> <?php
                                ?>
                            </div>


                        </div>
                        </p>
                        <a href="<?= $app_url ?>/views/account/edit" class="btn btn-default"><i class="fa fa-wrench"
                                                                                                aria-hidden="true"></i>
                            Adres wijzigen</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Account</h4>
                        <p class="card-text">
                        <div class="row">
                            <div class="col-md-6">

                                Status: <br>
                                Soort rekening: <br>
                                Rekeningnummer: <br>
                            </div>
                            <div class="col-md-6">
                                <?php if ($_SESSION['merchant'] == 1) {
                                    ?>
                                    <span class="badge badge-pill badge-info">Verkoper</span><br>

                                    <?php
                                } else {
                                    ?>
                                    <span class="badge badge-pill badge-primary">Gebruiker</span><br>
                                    <?php
                                }
                                ?>

                                Creditcard<br>
                                1234567890<br>
                            </div>
                        </div>
                        </p>
                        <?php if ($_SESSION['merchant'] == 1) {
                            echo "<a href='" . $app_url . "/views/merchant/API.php' class='btn btn-default'><i class='fa fa-wrench' aria-hidden='true'></i> Gegevens wijzigen</a>";
                        } else {
                            echo "<a href='" . $app_url . "/views/merchant/API.php' class='btn btn-success'>Upgraden</a>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container-float"><h1>Veilingen</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Laatste biedingen</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Bedrag</th>
                    <th>Status</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            Dummy data 1234567890-3456
                        </td>
                        <td>empty</td>
                        <td>empty</td>
                        <td><span class="badge badge-success">Veiling gewonnen</span></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="#"><i class="fa fa-info"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>empty</td>
                        <td>10-03-2017</td>
                        <td>€7,75</td>
                        <td><span class="badge badge-danger">Veiling verloren</span></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="#"><i class="fa fa-info"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>20164987</td>
                        <td>24-12-2016</td>
                        <td>€66,60</td>
                        <td><span class="badge badge-success">Veiling gewonnen</span></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="#"><i class="fa fa-info"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h3>Mijn veilingen</h3>
                <table class="table table-striped table-bordered">
                    <?php
                    $user = $_SESSION['username'];
                    $stmt = $pdo->prepare("SELECT COUNT(Seller) FROM Object WHERE seller = ?");
                    $stmt->execute([$user]);
                    $aantalVeilingen = $stmt->fetchColumn();
                    if ($aantalVeilingen > 0) {
                        ?>
                        <thead>
                        <th>Titel</th>
                        <th>Datum</th>
                        <th>Huidig bod</th>
                        <th>Status</th>
                        <th>Bewerk</th>
                        </thead>
                        <?php
                    } else {
                        echo '<th>U heeft nog geen veilingen geplaatst</th>';
                    }
                    ?>

                    <?php
                    $user = $_SESSION['username'];
                    $stmt = $pdo->prepare("SELECT * FROM Object WHERE seller = ?");
                    $stmt->execute([$user]);
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($aantalVeilingen > 0) {
                        foreach ($data as $d) { ?>
                            <td> <?php echo $d['title']; ?></td>
                            <td> <?php echo $d['durationbeginDay']; ?></td>
                            <td>€<?php echo $d['sellingprice']; ?></td>
                            <?php
                            if (date("Y-m-d") <= $d['durationendDay']) {
                                ?>
                                <td><span class="badge badge-success">Actief</span></td>
                                <?php
                            } else {
                                ?>
                                <td><span class="badge badge-danger">Gesloten</span></td>
                                <?php
                            }
                            ?>

                            <td>
                                <a class="btn btn-default btn-sm" href="changeAd.php?id=<?= $d['productid']; ?>"><i class="fa fa-wrench"
                                                                              style="width: 12px"></i></a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteModal" data-ad="<?php echo $d['productid']; ?>"><i
                                            class="fa fa-trash-o fa-sm"></i></button>
                            </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php

    include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');
}
else {
    include($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
}


?>
<!-- /views/account/index.php -->

<?php
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
                        <div class="card-text">
                            <table class="table table-userdata">
                                <tbody>
                                <tr>
                                    <td>Gebruikersnaam:</td>
                                    <td><?= $user['username'] ?></td>
                                </tr>
                                <tr>
                                    <td>Voornaam:</td>
                                    <td><?= $user['firstname'] ?></td>
                                </tr>
                                <tr>
                                    <td>Achternaam:</td>
                                    <td><?= $user['lastname'] ?></td>
                                </tr>
                                <tr>
                                    <td>Email adres:</td>
                                    <td><?= $user['email'] ?></td>
                                </tr>
                                <tr>
                                    <td>Geboortedatum:</td>
                                    <td><?= $user['birthday'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
                        <div class="card-text">
                            <table class="table table-userdata">
                                <tbody>
                                <tr>
                                    <td>Straat:
                                    <td>
                                    <td><?= $user['address1'] ?></td>
                                </tr>
                                <tr>
                                    <td>Postcode:
                                    <td>
                                    <td><?= $user['zipcode'] ?></td>
                                </tr>
                                <tr>
                                    <td>Plaats:
                                    <td>
                                    <td><?= $user['city'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
                        <div class="card-text">
                            <table class="table table-userdata">
                                <tbody>
                                <tr>
                                    <td>Status:</td>
                                    <td><?php if ($user['merchant'] == 1) {
                                            ?>
                                            <span class="badge badge-pill badge-info">Verkoper</span><br>

                                            <?php
                                        } else {
                                            ?>
                                            <span class="badge badge-pill badge-primary">Gebruiker</span><br>
                                            <?php
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td>Soort rekening:</td>
                                    <td>Creditcard</td>
                                </tr>
                                <tr>
                                    <td>Rekeningnummer:</td>
                                    <td>1234567890</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($user['merchant'] == 1) {
                            echo "<a href='" . $app_url . "/views/merchant/changeData.php' class='btn btn-default'><i class='fa fa-wrench' aria-hidden='true'></i> Gegevens wijzigen</a>";
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
                    $username = $_SESSION['username'];
                    $stmt = $pdo->prepare("SELECT COUNT(Seller) FROM Object WHERE seller = ?");
                    $stmt->execute([$username]);
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
                        global $user;
                        echo '<th>U heeft nog geen veilingen geplaatst.</th>';
                    }
                    ?>

                    <?php
                    $username = $_SESSION['username'];
                    $stmt = $pdo->prepare("SELECT * FROM Object WHERE seller = ?");
                    $stmt->execute([$username]);
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($aantalVeilingen > 0) {
                        foreach ($data as $d) { ?>
                            <td> <?php echo $d['title']; ?></td>
                            <td> <?php echo $d['durationbeginDay']; ?></td>
                            <td>€<?php echo $d['sellingprice']; ?></td>
                            <?php
                            $date1 = new DateTime(date("Y-m-d h:i:s"));
                            $date2 = new DateTime($d['durationendDay'] . ' ' . $d['durationendTime']);
                            if ($date1 <= $date2){
                                ?>
                                <td><span class="badge badge-success">Actief</span></td>
                                <?php
                            } else {
                                ?>
                                <td><span class="badge badge-danger">Gesloten</span></td>
                                <?php
                                $productidToDelete = $d['productid'];
                                $stmt = $pdo->prepare("UPDATE Object SET auctionClosed = 1 WHERE productid = ?");
                                $stmt->execute([$productidToDelete]);
                            }
                            ?>

                            <td>
                                <form action="changeAd.php" method="post">
                                    <button class="btn btn-default btn-sm" name="changeid"
                                            value="<?= $d['productid'] ?>"><i
                                                class="fa fa-wrench"
                                                style="width: 12px"></i></button>
                                </form>
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
    include($_SERVER['DOCUMENT_ROOT'] . '/views/account/removeAd.php');
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
}


?>
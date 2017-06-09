<!-- /views/account/index.php -->

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

// Start a session
session_start();

// Check if user is already logged on. If yes, redirect to accountpage.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

if ($debug == false) {
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

$merchantStatus = false;

GLOBAL $User;

//Zet alle advertenties op gesloten van de gebande gebruikers
$stmt = $pdo-> prepare ("update object set auctionClosed = 1 
                                  where Seller IN (select username from users where banned = 1)");
$stmt-> execute();



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
                <div class="myBids" style="overflow: auto; height: 20em;">
                    <table class="table table-striped table-bordered">
                        <?php
                        $username = $_SESSION['username'];
                        $stmt = $pdo->prepare("SELECT COUNT([user]) FROM bidding WHERE [user] = ?");
                        $stmt->execute([$username]);
                        $aantalBiedingen = $stmt->fetchColumn();
                        if($aantalBiedingen == 0){
                            print'<tr>
                                  <thead>
                                      <th class="table-danger">U heeft nog geen biedingen geplaatst.</th>
                                  </thead>
                                  </tr>';
                        }

                        else if($aantalBiedingen >= 1){
                            print '<tr>
                                    <thead>
                                        <th>ID</th>
                                        <th>Datum</th>
                                        <th>Bedrag</th>
                                        <th>Tijd</th>
                                   </thead>
                                   </tr>';
                        }
                        print '</tr>';
                        ?>
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM bidding WHERE [user] = ?");
                        $stmt->execute([$username]);
                        $dataBiedingen = $stmt->fetchAll();
                        foreach($dataBiedingen as $d){ ?>
                            <tr>
                            <?php echo '<td>' . $d['productid'] . '</td>'; ?>
                            <?php echo '<td>' . $d['biddingday'] . '</td>'; ?>
                            <?php echo '<td> € ' . $d['biddingprice'] . '</td>'; ?>
                            <?php echo '<td>' . $d['biddingtime'] . '</td>'; ?>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h3>Mijn veilingen</h3>
                <div class="myAuctions" style="overflow: auto; height: 20em;">
                    <table class="table table-striped table-bordered">
                        <?php
                        $username = $_SESSION['username'];
                        $stmt = $pdo->prepare("SELECT COUNT(Seller) FROM Object WHERE seller = ?");
                        $stmt->execute([$username]);
                        $aantalVeilingen = $stmt->fetchColumn();
                        if ($aantalVeilingen > 0) {
                            ?>
                            <tr>
                                <thead>
                                <th>Titel</th>
                                <th>Datum</th>
                                <th>Huidig bod</th>
                                <th>Status</th>
                                <th>Bewerk</th>
                                </thead>
                            </tr>
                            <?php
                        } else {
                            global $user;
                            ?>
                            <th class="table-danger">U heeft nog geen veilingen geplaatst.</th>
                            <?php
                        }
                        ?>

                        <?php
                        $username = $_SESSION['username'];
                        $stmt = $pdo->prepare("SELECT * FROM Object WHERE seller = ?");
                        $stmt->execute([$username]);
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($aantalVeilingen > 0) {
                            foreach ($data as $d) { ?>
                                <tr>
                                    <td> <?php echo $d['title']; ?></td>
                                    <td> <?php echo $d['durationbeginDay']; ?></td>
                                    <?php
                                    $id = $d['productid'];
                                    $stmt = $pdo->prepare("SELECT MAX(biddingprice) FROM Bidding WHERE productid = ?");
                                    $stmt->execute([$d['productid']]);
                                    $dataId = $stmt->fetchColumn();
                                    if ($dataId == NULL) {
                                        print '<td><i>Nog geen biedingen</i></td>';
                                    } else {
                                        ?>
                                        <td>€ <?= $dataId; ?></td>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($d['auctionClosed'] == 1){
                                        print '<td><span class="badge badge-danger">Gesloten</span></td>';
                                    }
                                    else {
                                        $date1 = date("Y-m-d H:i:s");
                                        $date2 = $d['durationendDay'] . ' ' . $d['durationendTime'];
                                        if (strtotime($date1) <= strtotime($date2)) {
                                            ?>
                                            <td><span class="badge badge-success">Actief</span></td>
                                            <?php
                                        } else {
                                            ?>
                                            <td><span class="badge badge-danger">Gesloten</span></td>
                                            <?php

                                        }
                                    }
                                    ?>
                                    <td>
                                        <form action="<?= $app_url . '/views/account/update-advertisement.php'?>" method="post">
                                            <button class="btn btn-default btn-sm" name="changeid"
                                                    value="<?= $d['productid'] ?>"><i
                                                    class="fa fa-wrench"
                                                    style="width: 12px;"></i></button>
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
    </div>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/views/account/removeAd.php');
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/include/login-message.inc.php');
}

if($user['banned'] == 1){
    session_destroy();
    header('location: login.php');
}


?>
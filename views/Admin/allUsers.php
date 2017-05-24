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

if (isset($user['username']) && $user['admin'] == 1) {
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin paneel</title>
        <link rel="stylesheet" type="text/css" href="<?= $_SERVER['DOCUMENT_ROOT']?>/assets/css/scrollableContent.css"
    </head>

    <body>
    <h1><?php echo 'Welkom Admin'; ?></h1>
    <div class="content">
        <br>
        <h3>Alle gebruikers</h3>
        <br>
        <div class="allUsers" style="overflow: auto; height: 21em;">
            <table class="table table-sm table-striped table-bordered" style="height: 15em; overflow-x: auto; overflow-y: auto;">
                <?php
                $username = $_SESSION['username'];
                $stmt = $pdo->prepare("SELECT COUNT(username) FROM Users WHERE username = ?");
                $stmt->execute([$username]);
                $aantalGebruikers = $stmt->fetchColumn();
                if ($aantalGebruikers> 0) {
                    ?>
                    <thead>
                    <th>Gebruikersnaam</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>Adres 1</th>
                    <th>Adres 2</th>
                    <th>Postcode</th>
                    <th>Stad</th>
                    <th>Land</th>
                    <th>Geboortedatum</th>
                    <th>Email</th>
                    <th>Vraag nummer</th>
                    <th>Antwoord</th>
                    <th>Verkoper</th>
                    <th>Admin</th>
                    <th>Bewerk</th>
                    </thead>
                    <?php
                } else {
                    echo '<th>Er zijn nog geen geregistreerde gebruikers.</th>';
                }
                ?>

                <?php
                $username = $_SESSION['username'];
                $stmt = $pdo->prepare("SELECT * FROM Users");
                $stmt->execute([$username]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($aantalGebruikers > 0) {
                    foreach ($data as $d) { ?>
                        <tr>
                            <td> <?php echo $d['username']; ?></td>
                            <td> <?php echo $d['firstname']; ?></td>
                            <td> <?php echo $d['lastname']; ?></td>
                            <td> <?php echo $d['address1']; ?></td>
                            <td> <?php echo $d['address2']; ?></td>
                            <td> <?php echo $d['zipcode']; ?></td>
                            <td> <?php echo $d['city']; ?></td>
                            <td> <?php echo $d['country']; ?></td>
                            <td> <?php echo $d['birthday']; ?></td>
                            <td> <?php echo $d['email']; ?></td>
                            <td> <?php echo $d['questionnumber']; ?></td>
                            <td> <?php echo $d['answer']; ?></td>
                            <?php
                                if($d['merchant'] == 1){
                                    ?>
                                    <td> Ja </td>
                                    <?php
                                }
                                else {
                                    ?>
                                    <td> Nee </td>
                                    <?php
                                }
                            ?>
                            <?php
                            if($d['admin'] == 1){
                                ?>
                                <td> Ja </td>
                                <?php
                            }
                            else {
                                ?>
                                <td> Nee </td>
                                <?php
                            }
                            ?>
                            <td>
                                <a class="btn btn-default btn-sm" href="changeAd.php?id=<?= $d['username']; ?>"><i
                                            class="fa fa-wrench"
                                            style="width: 12px"></i></a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteModal" data-user="<?php echo $d['username']; ?>"><i
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

    </body>

    </html>

    <?php

    include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-user.php');
} else {
    header ('location: ../account/login.php');
}


?>
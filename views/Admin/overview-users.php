<!-- /views/account/index.php -->

<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

// Shows errors on page when 'Debugging' is enabled.
include($_SERVER['DOCUMENT_ROOT'] . '/app/debug.php');

$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM Users");
$stmt->execute([$username]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

$merchantStatus = false;

if (isset($user['username']) && $user['admin'] == 1) {
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Adminpaneel</title>
    </head>

    <body>
    <div class="container-float">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Overzicht gebruikers</li>
        </ol>
    </div>

    <h1><?php echo 'Welkom Admin'; ?></h1>
    <div class="content">
        <br>
        <h3>Overzicht gebruikers</h3>
        <br>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(username) FROM Users");
        $stmt->execute();
        $aantalGebruikers = $stmt->fetchColumn();
        if ($aantalGebruikers > 0) {
            if ($aantalGebruikers == 1) {
                ?>
                <div class="alert alert-info alert-dismissible fade show" data-dismissal="alert" role="alert">Er
                    is <strong><?= $aantalGebruikers; ?></strong> geregistreerde gebruiker.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-info alert-dismissible fade show" data-dismissal="alert" role="alert">Er
                    zijn <strong><?= $aantalGebruikers; ?></strong> geregistreerde gebruikers.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            ?>

            <!-- jQuery Live Searchform -->
            <form id="live-search" action="" class="styled" method="post">
                <fieldset>
                    <input type="text" class="text-input" id="filter" value="" placeholder="Zoek naar gebruiker(s)" />
                    <span id="filter-count"></span>
                </fieldset>
            </form>

            <div class="allUsers" style="overflow-x: auto; overflow-y: auto; height: 100%;">
                <table class="table table-sm table-striped table-bordered"
                       style="overflow-x: auto; overflow-y: auto; height: 100%;">

                    <thead>
                        <th>Gebruikersnaam</th>
                        <th>Voornaam</th>
                        <th>Achternaam</th>
    <!--                    <th>Adres 1</th>-->
    <!--                    <th>Adres 2</th>-->
    <!--                    <th>Postcode</th>-->
                        <th>Stad</th>
                        <th>Land</th>
    <!--                    <th>Geboortedatum</th>-->
                        <th>Email</th>
                        <th>Functie</th>
                        <th>Beheerder</th>
                        <th>Bewerk</th>
                    </thead>
             <?php }
            elseif ($aantalGebruikers == 0) {
                echo '<th>Er zijn nog geen geregistreerde gebruikers.</th>';
            }

            // Foreach loop to show all users
            if ($aantalGebruikers > 0) {
                foreach ($data as $d) { ?>
                    <tbody>
                        <tr>
                            <td> <?php echo $d['username']; ?></td>
                            <td> <?php echo $d['firstname']; ?></td>
                            <td> <?php echo $d['lastname']; ?></td>
    <!--                        <td> --><?php //echo $d['address1']; ?><!--</td>-->
    <!--                        <td> --><?php //echo $d['address2']; ?><!--</td>-->
    <!--                        <td> --><?php //echo $d['zipcode']; ?><!--</td>-->
                            <td> <?php echo $d['city']; ?></td>
                            <td> <?php echo $d['country']; ?></td>
    <!--                        <td> --><?php //echo $d['birthday']; ?><!--</td>-->
                            <td> <?php echo $d['email']; ?></td>
                            <?php
                                if($d['banned'] == 1) {
                                    echo "<td><span class=\"badge badge-danger\">Verbannen</span></td>";
                                } else {
                                    if($d['merchant'] == 1){
                                        echo "<td><span class=\"badge badge-primary\">Verkoper</span></td>";
                                    }
                                    else {
                                        echo "<td><span class=\"badge badge-default\">Bezoeker</span></td>";
                                    }
                                }

                                if($d['admin'] == 1){
                                    echo "<td><span class=\"badge badge-success\">Beheerder</span></td>";
                                } else {
                                    echo "<td><span class=\"badge badge-default\">Nee</span></td>";
                                }
                                ?>

                            <td>
                                <form action="<?=$app_url?>/views/account/update-account.php" method="post" style="display:inline;">
                                    <button <?php if($d['admin'] == 1): ?>disabled<?php endif; ?> class="btn btn-primary btn-sm" name="changeusername" title="Bewerk account" value="<?= $d['username']?>"><i class="fa fa-wrench" style="width: 12px"></i></button>
                                </form>

                                    <button <?php if($d['admin'] == 1): ?>disabled<?php endif; ?> type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" title="Verwijderen" data-user="<?php echo $d['username']; ?>"><i class="fa fa-trash-o fa-sm"></i> </button>
                                    <?php if($d['banned'] == 1): ?>
                                        <button <?php if($d['admin'] == 1): ?>disabled<?php endif; ?> type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#unbanModal" title="Ban verwijderen" data-user="<?php echo $d['username']; ?>"><i class="fa fa-undo fa-sm"></i> </button>
                                    <?php else: ?>
                                        <button <?php if($d['admin'] == 1): ?>disabled<?php endif; ?> type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#banModal" title="Bannen" data-user="<?php echo $d['username']; ?>"><i class="fa fa-ban fa-sm"></i> </button>
                                    <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                    <?php
                }
            }
            ?>
                    </tbody>
                </table>
            </div>
            <br>
            <br>
        </div>

        <!-- Add Livesearch.js -->
        <script src="<?=$cdn_url?>/assets/js/livesearch.js"></script>
    </body>

    <?php

    include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-user.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/ban-user.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/unban-user.php');
}
else {
    header ('location: ../account/login.php');
}


?>
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


if (isset($user['username']) && $user['admin'] == 1) {
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin paneel</title>
        <link rel="stylesheet" type="text/css" href="<?= $_SERVER['DOCUMENT_ROOT'] ?>/assets/css/scrollableContent.css"
    </head>

    <body>
    <h1><?php echo 'Welkom ' . $_SESSION['username']; ?></h1>
    <div class="content">
    <br>
    <h3>Alle actieve advertenties</h3>
    <br>
    <?php
    $stmt = $pdo->prepare("SELECT COUNT(Seller) FROM Object WHERE auctionClosed = 0");
    $stmt->execute();
    $activeAuctions = $stmt->fetchColumn();
    if ($activeAuctions > 0) {
    ?>
    <table class="table table-sm table-striped table-bordered" style="overflow-x: auto; overflow-y: auto;">
        <tbody>
        <thead>
        <th>Product id</th>
        <th>Titel</th>
        <th>Beschrijving</th>
        <th>Start prijs</th>
        <th>Betaal methode</th>
        <th>Betaal instructie</th>
        <th>Stad</th>
        <th>Land</th>
        <th>Lengte</th>
        <th>Datum</th>
        <th>Tijd</th>
        <th>Verzendkosten</th>
        <th>Verzend instructies</th>
        <th>Verkoper</th>
        <th>Koper</th>
        <th>Eind datum</th>
        <th>Eind tijd</th>
        <th>Status</th>
        <th>Verkoop prijs</th>
        <th>Categorie</th>
        <th>Bewerk</th>
        </thead>
        <?php
    } else if ($activeAuctions == 0) {
            print("<div class='alert alert-danger'><strong>Oei!</strong> Het lijkt erop dat er geen actieve advertenties zijn.</div>");
      }
        ?>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM Object WHERE auctionClosed = 0");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($activeAuctions > 0) {
            foreach ($data as $d) { ?>
                <tr>
                    <td> <?php echo $d['productid']; ?></td>
                    <td> <?php echo $d['title']; ?></td>
                    <td> <?php echo $d['description']; ?></td>
                    <td> <?php echo $d['startprice']; ?></td>
                    <td> <?php echo $d['paymentMethodNumber']; ?></td>
                    <td> <?php echo $d['paymentinstruction']; ?></td>
                    <td> <?php echo $d['city']; ?></td>
                    <td> <?php echo $d['country']; ?></td>
                    <td> <?php echo $d['duration']; ?></td>
                    <td> <?php echo $d['durationbeginDay']; ?></td>
                    <td> <?php echo $d['durationbeginTime']; ?></td>
                    <td> <?php echo $d['shippingcosts']; ?></td>
                    <td> <?php echo $d['shippinginstructions']; ?></td>
                    <td> <?php echo $d['Seller']; ?></td>
                    <td> <?php echo $d['Buyer']; ?></td>
                    <td> <?php echo $d['durationendDay']; ?></td>
                    <td> <?php echo $d['durationendTime']; ?></td>
                    <?php


                    //                    $date1 = new DateTime(date("Y-m-d h:i:s"));
                    //                    $date2 = new DateTime($d['durationendDay'] . ' ' . $d['durationendTime']);
                    //                    if ($date1 <= $date2){

                    $date1 = date("Y-m-d H:i:s");
                    $date2 = $d['durationendDay'] . ' ' . $d['durationendTime'];
                    if (strtotime($date1) <= strtotime($date2)) {
                        ?>
                        <td><span class="badge badge-success">Actief</span></td>
                        <?php

                    } else {
                        $productidToDelete = $d['productid'];
                        $stmt = $pdo->prepare("UPDATE Object SET auctionClosed = 1 WHERE productid = ?");
                        $stmt->execute([$productidToDelete]);
                        ?>
                        <td><span class="badge badge-danger">Gesloten</span></td>
                        <?php
                    }
                    ?>
                    <td><?php echo $d['sellingprice']; ?></td>
                    <td><?php echo $d['Categories']; ?></td>
                    <td>
                        <a class="btn btn-default btn-sm" href="../account/changeAd.php?id=<?= $d['productid']; ?>"><i
                                    class="fa fa-wrench"
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



        <!-- =========================================================== !-->




    <br>
    <br>
    <br>
    <h3>Alle gesloten advertenties</h3>
    <br>
    <?php
    $stmt = $pdo->prepare("SELECT COUNT(Seller) FROM Object WHERE auctionClosed = 1");
    $stmt->execute();
    $closedAuctions = $stmt->fetchColumn();
    if ($closedAuctions > 0) {
    ?>
    <table class="table table-sm table-striped table-bordered"
           style="height: 15em; overflow-x: auto; overflow-y: auto;">
        <tbody>
        <thead>
        <th>Product id</th>
        <th>Titel</th>
        <th>Beschrijving</th>
        <th>Start prijs</th>
        <th>Betaal methode</th>
        <th>Betaal instructie</th>
        <th>Stad</th>
        <th>Land</th>
        <th>Lengte</th>
        <th>Datum</th>
        <th>Tijd</th>
        <th>Verzendkosten</th>
        <th>Verzend instructies</th>
        <th>Verkoper</th>
        <th>Koper</th>
        <th>Eind datum</th>
        <th>Eind tijd</th>
        <th>Status</th>
        <th>Verkoop prijs</th>
        <th>Categorie</th>
        <th>Bewerk</th>
        </thead>
        <?php
    } else if ($closedAuctions == 0) {
            print("<div class='alert alert-danger'><strong>Oei!</strong> Het lijkt erop dat er geen gesloten advertenties zijn.</div>");
      }
        ?>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM Object WHERE auctionClosed = 1");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($closedAuctions > 0) {
            foreach ($data as $d) { ?>
                <tr>
                    <td> <?php echo $d['productid']; ?></td>
                    <td> <?php echo $d['title']; ?></td>
                    <td> <?php echo $d['description']; ?></td>
                    <td> <?php echo $d['startprice']; ?></td>
                    <td> <?php echo $d['paymentMethodNumber']; ?></td>
                    <td> <?php echo $d['paymentinstruction']; ?></td>
                    <td> <?php echo $d['city']; ?></td>
                    <td> <?php echo $d['country']; ?></td>
                    <td> <?php echo $d['duration']; ?></td>
                    <td> <?php echo $d['durationbeginDay']; ?></td>
                    <td> <?php echo $d['durationbeginTime']; ?></td>
                    <td> <?php echo $d['shippingcosts']; ?></td>
                    <td> <?php echo $d['shippinginstructions']; ?></td>
                    <td> <?php echo $d['Seller']; ?></td>
                    <td> <?php echo $d['Buyer']; ?></td>
                    <td> <?php echo $d['durationendDay']; ?></td>
                    <td> <?php echo $d['durationendTime']; ?></td>
                    <td><span class="badge badge-danger">Gesloten</span></td>
                    <td><?php echo $d['sellingprice']; ?></td>
                    <td><?php echo $d['Categories']; ?></td>
                    <td>
                        <a class="btn btn-default btn-sm" href="../account/changeAd.php?id=<?= $d['productid']; ?>"><i
                                    class="fa fa-wrench"
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

    </body>
    </html>

    <?php
}
    include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');




?>
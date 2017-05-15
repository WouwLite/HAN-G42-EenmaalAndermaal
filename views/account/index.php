<!-- /views/account/index.php -->

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

if ($debug == false) {
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
}

include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

$merchantStatus = false;

/*
 * Einde PHP variable-area
 */
?>
<div class="container-float">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
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

                        </div>
                        <div class="col-md-6">

                        </div>


                    </div>
                    </p>
                    <a href="<?=$app_url?>/views/account/<?=$id?>/edit" class="btn btn-default"><i class="fa fa-wrench" aria-hidden="true"></i> Gegevens wijzigen</a>
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

                        </div>
                        <div class="col-md-6">

                        </div>


                    </div>
                    </p>
                    <a href="<?=$app_url?>/views/account/<?=$id?>/edit" class="btn btn-default"><i class="fa fa-wrench" aria-hidden="true"></i> Adres wijzigen</a>
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

                            <span class="badge badge-pill badge-primary">Gebruiker</span><br>
                            Creditcard<br>
                            1234567890<br>
                        </div>


                    </div>
                    </p>
                    <?php if($merchantStatus) {
                        echo "<a href='" . $app_url . "/views/account/" . $id . "/edit' class='btn btn-default'><i class='fa fa-wrench' aria-hidden='true'></i> Gegevens wijzigen</a>";
                    } else {
                        echo "<a href='" . $app_url . "/views/account/" . $id . "/edit' class='btn btn-success'>Upgraden</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <td>20170666</td>
                    <td>06-04-2017</td>
                    <td>€48,00</td>
                    <td><span class="badge badge-success">Veiling gewonnen</span></td>
                    <td>
                        <a class="btn btn-info btn-sm" href="#"><i class="fa fa-info"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>20170603</td>
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
                <thead>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Huidig bod</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>201705121</td>
                        <td>12-05-2017</td>
                        <td>€11,50</td>
                        <td><span class="badge badge-success">Actief</span></td>
                        <td>
                            <a class="btn btn-default btn-sm" href="#"><i class="fa fa-wrench" style="width: 12px"></i></a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteModal" data-ad="1"><i class="fa fa-trash-o fa-sm"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>201612129</td>
                        <td>12-12-2016</td>
                        <td>€6,66</td>
                        <td><span class="badge badge-danger">Gesloten</span></td>
                        <td>
                            <a class="btn btn-default btn-sm" href="#"><i class="fa fa-wrench" style="width: 12px"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/delete-modal.php');

?>

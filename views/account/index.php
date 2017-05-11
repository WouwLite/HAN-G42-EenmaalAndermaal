<!-- /views/account/index.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

/*
 * Voer hieronder eventuele extra PHP variables toe
 */

$accountInfo = new Account();
$merchantStatus = true;

class Account {
    public function showBasicInfo() {
        return $accountArray = array(
            'firstname'     => 'Henk',
            'lastname'      => 'Stieltjes',
            'phone'         => '06-123456789',
            'email'         => 'henk@stieltjes.nl',
            'birthday'      => '01-01-1975',
        );
    }

    public function showAddress() {
        return $accountArray = array(
            'street'        => 'Postubs 150',
            'PC'            => '6500 AD',
            'city'          => 'Nijmegen',
            'county'        => 'Gelderland',
            'country'       => 'Netherlands',
        );
    }

    public function showHistory() {
        return $accountArray = array(
            0 => array(
                'id'        => '20171001',
                'date'      => '01-01-2017',
                'price'     => '66,60',
                'status'    => 'fap'
            )
        );
    }

    public function showAccountStatus() {
        return $accountStatus = array(
            'status'        => 'Verkoper',
        );
    }
}

/*
 * Einde PHP variable-area
 */
?>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Mijn Account</li>
</ol>

<div class="container"><h1>Mijn account</h1></div>
<div class="row">
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            <div class="card-block">
                <h4 class="card-title">Gegevens</h4>
                <p class="card-text">
                <div class="row">
                    <div class="col-md-6">
                        <?php foreach($accountInfo->showBasicInfo() as $key => $value) {
                            echo ucwords($key) . ": <br>";
                        } ?>
                    </div>
                    <div class="col-md-6">
                        <?php foreach($accountInfo->showBasicInfo() as $key => $value) {
                            echo $value . "<br>";
                        } ?>
                    </div>


                </div>
                </p>
                <a href="<?=$app_url?>/views/account/<?=$id?>/edit" class="btn btn-default"><i class="fa fa-wrench" aria-hidden="true"></i> Gegevens wijzigen</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            <div class="card-block">
                <h4 class="card-title">Adres</h4>
                <p class="card-text">
                <div class="row">
                    <div class="col-md-6">
                        <?php foreach($accountInfo->showAddress() as $key => $value) {
                            echo ucwords($key) . ": <br>";
                        } ?>
                    </div>
                    <div class="col-md-6">
                        <?php foreach($accountInfo->showAddress() as $key => $value) {
                            echo $value . "<br>";
                        } ?>
                    </div>


                </div>
                </p>
                <a href="<?=$app_url?>/views/account/<?=$id?>/edit" class="btn btn-default"><i class="fa fa-wrench" aria-hidden="true"></i> Adres wijzigen</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            <div class="card-block">
                <h4 class="card-title">Account</h4>
                <p class="card-text">
                <div class="row">
                    <div class="col-md-6">
<!--                        --><?php //foreach($accountInfo->showAccountStatus() as $key => $value) {
//                            echo ucwords($key) . ": <br>";
//                        } ?>
                        Status: <br>
                        Soort rekening: <br>
                        Rekeningnummer: <br>
                    </div>
                    <div class="col-md-6">
<!--                        --><?php //foreach($accountInfo->showAccountStatus() as $key => $value) {
//                            echo "<span class=\"badge badge-pill badge-primary\">" . $value . "</span><br>";
//                        } ?>
                        <span class="badge badge-pill badge-primary">Verkoper</span><br>
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
<div class="container"><h1>History</h1></div>
<div class="row">
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            <div class="card-block">
                <h4 class="card-title">Laatste biedingen</h4>
                <p class="card-text">
                <div class="row">
                    <div class="col-md-6">
                        <!--                        --><?php //foreach($accountInfo->showAccountStatus() as $key => $value) {
                        //                            echo ucwords($key) . ": <br>";
                        //                        } ?>
                        Status: <br>
                        Soort rekening: <br>
                        Rekeningnummer: <br>
                    </div>
                    <div class="col-md-6">
                        <!--                        --><?php //foreach($accountInfo->showAccountStatus() as $key => $value) {
                        //                            echo "<span class=\"badge badge-pill badge-primary\">" . $value . "</span><br>";
                        //                        } ?>
                        <span class="badge badge-pill badge-primary">Verkoper</span><br>
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




    <div class="col-md-6">
        <h3>Laatste biedingen</h3>
        <table class="table table-striped table-bordered">
            <thead>
            <th>ID</th>
            <th>Datum</th>
            <th>Bedrag</th>
            <th>Status</th>
            </thead>
            <tbody>
            <?php foreach($accountInfo->showHistory() as $key => $value)
                echo "<tr>";
            echo "<td>" . $value[1] . "</td>";
            echo "<td>" . $history[0][1] . "</td>";
            echo "<td>" . $history[0][2] . "</td>";
            echo "<td><span class=\"badge badge-default\">" . $history[0][3] . "</span></td>";
            echo "</tr>"
            ?>
            <tr>
                <td>20170603</td>
                <td>10-03-2017</td>
                <td>€7,75</td>
                <td><span class="badge badge-danger">Verloren</span></td>
            </tr>
            <tr>
                <td>20164987</td>
                <td>24-12-2016</td>
                <td>€66,60</td>
                <td><span class="badge badge-success">Gewonnen</span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>

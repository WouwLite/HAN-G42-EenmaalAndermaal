<!-- /views/account/index.php -->

<?php
/*
 * Voer hieronder eventuele extra PHP variables toe
 */

// Voeg developmentfuncties toe, svp niet verwijderen
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
//include($_SERVER['DOCUMENT_ROOT'] . '/dev/functions.dev.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

// Voeg breadcrumbs toe (https://v4-alpha.getbootstrap.com/components/breadcrumb/)
$breadcrumbs = array("Thuis", "Mijn Account");
$breadcrumb_active = "Overzicht";

// Classes and functions
//$info = array('name' => 'Henk', 'age' => '32', 'gender' => 'Male',);
//$accountClass = new AccountClass();
//class AccountClass {
//    public function showAllUsers(array $info) {
//        echo "<ul>";
//        foreach ($info as $key => $value) {
//            echo "<li>" . ucwords($key) . ": " . $value . "</li>";
//        }
//        echo "</ul>";
//    }
//}
//
//$classTest = new Test();
//
//class Test {
//    public function showTest(string $num) {
//        return $num;
//    }
//}

$accountInfo = new Account();

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

<div class="row">
    <div class="col-md-4">
        <h1>Mijn account</h1>
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

        <br>

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
    <div class="col-md-2"></div>
    <div class="col-md-6">
        <h1>Mijn historie</h1>
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
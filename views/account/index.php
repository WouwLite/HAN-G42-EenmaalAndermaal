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
            array('201710001', '201710010', '201710100', '20171000'),
            array('01-01-2016', '10-02-2017', '12-03-2017', '10-05-2017'),
            array('17,50', '66,60', '120,00', '12,50'),
            array('actief', 'actief', 'verloren', 'gewonnen')
//            1 = actief, 4 = verloren, 5 = gewonnen
        );
    }

    public function foreachHistory() {
        foreach(showHistory() as $history)
            echo "<td>" . $history[0] . "</td>";
            echo "<td>" . $history[1] . "</td>";
            echo "<td>" . $history[2] . "</td>";
            echo "<td><span class=\"badge badge-default\">" . $history[3] . "</span></td>";
    }
}

/*
 * Einde PHP variable-area
 */
?>
<div class="row">
    <div class="col-md-4">
        <h1>Mijn account</h1>
        <h3>Gegevens</h3>
        <ul>
            <?php foreach($accountInfo->showBasicInfo() as $key => $value)
                echo "<li>" . ucwords($key) . ": " . $value . "</li>";
            ?>
        </ul>
        <br><br>
        <h3>Adresgegevens</h3>
        <ul>
            <?php foreach($accountInfo->showAddress() as $key => $value)
                echo "<li>" . ucwords($key) . ": " . $value . "</li>";
            ?>
        </ul>
<!--        --><?//=$accountInfo->showBasicInfo($accountArray)?>
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
                <tr>
                    <?php foreach($accountInfo->showHistory() as $history)
                        echo "<td>" . $history[0] . "</td>";
                        echo "<td>" . $history[1] . "</td>";
                        echo "<td>" . $history[2] . "</td>";
//                        if($history[3] == 1) {
//                            echo '<td><span class="badge badge-default">Actief</span></td>';
//                        } elseif($history[3] == 4) {
//                            echo "4";
//                        }
                        echo "<td><span class=\"badge badge-default\">" . $history[3] . "</span></td>";
                    ?>
<!--                    <td>20170001</td>-->
<!--                    <td>10-05-2017</td>-->
<!--                    <td>€12,50</td>-->
<!--                    <td><span class="badge badge-default">Actief</span></td>-->
                </tr>
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
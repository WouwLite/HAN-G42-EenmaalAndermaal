<!-- /views/account/delete-advertisement.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/getpost.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
function getAds()
{
    global $pdo;
    $stmt = $pdo->query("select productid, durationendDay, durationendTime from Object");
    $ads = array();
    while ($row = $stmt->fetch()) {
        $ad = array($row['productid'], $row['durationendDay'], $row['durationendTime']);
        $ads[] = $ad;
    }
    return $ads;
}
echo $_SESSION['username'];
//removeAds();
function removeAds()
{
    global $pdo;
    $delsql = "DELETE FROM Object WHERE durationendDay < getDate()";
    $delstmt = $pdo->prepare($delsql);
    $delstmt->execute();
}
/*
$stmt = $pdo->prepare("SELECT * FROM Object WHERE seller = ?");
$stmt->execute([$user]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (date("Y-m-d") > $data['durationendDay']) {
    global $pdo;
    $pdo->query("delete from Object where seller = " . $_SESSION['username'] . " AND durationendDay < " . date("Y-m-d") . "");

    echo "Uw advertentie wordt nu verwijderd";
}
*/
?>
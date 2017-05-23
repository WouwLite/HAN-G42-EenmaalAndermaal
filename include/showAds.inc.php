<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
 require_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
function getAds()
{
    global $pdo;
    $sql = "select top 25 Title, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories  from Object";
    $result = $pdo->query($sql);
    $Ads = array();
    while ($row = $result->fetch()) {
        $Ad = array($row['Title'], $row['description'], $row['Categories']);
        $Ads[] = $Ad;
    }
    return $Ads;
}

$Ads = getAds();
$i = 0;
echo "<div class='container'>";
foreach ($Ads as $Adverts) {
    if ($i == 0) {
        echo "<div class='row'>";
    }
    $i++;
    echo "<div class='col-sm-4'>";
    echo "<h2>" . $Adverts[0] . "</h2>";
    if ($i == 5) {
        $i = 0;
        echo "</div>";
    }
    echo "</div>";
}
echo "</div>";
?>




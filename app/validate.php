<?php
session_reset();
session_start([
    'cookie_lifetime' => 86400,
]);

if(!isset($_SESSION['username'])){
    echo "Er is géén username";
    echo $_SESSION['username'];
} else {
    echo "Er is wél een username<br>";
    echo $_SESSION['username'];
}

//if ($_SESSION['email'] == empty($_SESSION['email'])) {
//    echo "if username is set in the database, show this.";
//    echo "<h1 style='background-color: red; color: white;'>VALIDATIE NEGATIEF</h1>";
////    header("Refresh:0; url=/dev/validate-test.php");
//} else {
//    echo "if user is present in DB, do nothing.";
//    echo "<h1 style='background-color: lawngreen;'>VALIDATIE OK</h1>";
////    header("Refresh:0; url=/index.php");
//}

// Show session information
$a = session_id();
if(empty($a)) session_start();
echo "SID: ".SID."<br>session_id(): <br>".session_id()."<br>COOKIE: <br>".$_COOKIE["PHPSESSID"];


?>
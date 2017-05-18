<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
global $user;
$user = $_SESSION['username'];
echo $user;
function updateData()
{
    global $vars, $pdo;
    $user = $_SESSION['username'];
    $sql= "UPDATE Users set merchant = 1 where username = '$user'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
updateData();

header('Location: http://iproject42.icasites.nl/views/account/index.php#');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

</body>
</html>

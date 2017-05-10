<!-- /config/database.php -->
<?php
$hostname = "mssql2.iproject.icasites.nl";
$dbname = "iproject42";
$username = "iproject42";
$pw = "7MqNNSxC";
try {
    $pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");
} catch (PDOException $e) {
    die($e->getMessage());
}
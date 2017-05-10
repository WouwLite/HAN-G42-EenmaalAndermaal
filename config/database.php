<!-- /config/database.php -->
<?php

$DB_NAME        = 'iproject42';
$DB_USERNAME    = 'iproject42';
$DB_PASSWD      = '7MqNNSxC';
$DB_HOST        = 'mssql2.iproject.icaites.nl';

try{
    $connection = new PDO("dblib:host=$DB_HOST;dbname=$DB_NAME;", $DB_USERNAME, $DB_PASSWD);
} catch(PDOException $e) {
    die ( "Connection failed!" . $e->getMessage());
}
<!-- /config/database.php -->

<?php

    /*
    |--------------------------------------------------------------------------
    | Database connection
    |--------------------------------------------------------------------------
    |
    | Change the filename to database.php (otherwise it won't work)
    | Than change the default database values according to your
    | settings and wishes.
    */

    $hostname   =   "hostname";
    $dbname     =   "dbname";
    $username   =   "username";
    $pw         =   "passwd";

// Don't change the values below!
try {
    $pdo = new PDO ("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");
} catch (PDOException $e) {
    die($e->getMessage());
}
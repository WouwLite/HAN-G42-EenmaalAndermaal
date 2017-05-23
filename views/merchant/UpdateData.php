<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/app/Mollie/API/Autoloader.php");
$mollie = new Mollie_API_Client;
$mollie->setApiKey('test_a27kq9WerzGjJNSCMfaPe73TTmzqD4');

$payment = $mollie->payments->get($_POST["id"]);
if ($payment->isPaid())
{
    $user = $payment->metadata->username;
    $sql = "UPDATE Users set merchant = 1 where username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user]);
}
?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>My Sexy Title</title>-->
<!--</head>-->
<!--<body>-->
<!---->
<!--</body>-->
<!--</html>-->

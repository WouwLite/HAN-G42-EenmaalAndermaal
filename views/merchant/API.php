<?php
session_start();
$_SESSION['amount'] = 00.01;
$_SESSION['orderNumber'] = 12345;
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/app.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/app/Mollie/API/Autoloader.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php');
$mollie = new Mollie_API_Client;
$mollie->setApiKey('test_a27kq9WerzGjJNSCMfaPe73TTmzqD4');


try {
    $payment = $mollie->payments->create(
        array(
            'amount' => $_SESSION['amount'],
            'description' => 'Upgrade naar verkoper',
            'redirectUrl' => 'http://iproject42.icasites.nl/views/merchant/UpdateData.php'.'',

            'metadata' => array(
                'order_id' => ''.$_SESSION['orderNumber'].'',
            )
        )
    );

    header("Location: " . $payment->getPaymentUrl());

    exit;
} catch (Mollie_API_Exception $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
    echo " on field " . htmlspecialchars($e->getField());
}
?>


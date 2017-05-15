<?php

session_start();
$_SESSION['amount'] = 00.01;
$_SESSION['orderNumber'] = 12345;
require_once 'Mollie/API/Autoloader.php';

$mollie = new Mollie_API_Client;
$mollie->setApiKey('test_a27kq9WerzGjJNSCMfaPe73TTmzqD4');

try {
    $payment = $mollie->payments->create(
        array(
            'amount' => $_SESSION['amount'],
            'description' => 'Upgrate naar verkoper',
            'redirectUrl' => 'https://webshop.example.org/order/'.$_SESSION['orderNumber'].'/',
            'metadata' => array(
                'order_id' => ''.$_SESSION['orderNumber'].'',
            )
        )
    );

    /*
    * Send the customer off to complete the payment.
    */
    header("Location: " . $payment->getPaymentUrl());
    exit;
} catch (Mollie_API_Exception $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
    echo " on field " . htmlspecialchars($e->getField());
}
?>


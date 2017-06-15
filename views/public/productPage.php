<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/style.inc.php');

global $user;
function getAd()
{
    global $url;
    $url = $_GET['link'];
    global $pdo;
    $result = $pdo->query("select o.productid, Title, seller, description, durationbeginDay, durationbeginTime, durationendDay, durationendTime, Categories, pp.filename,  (select max(biddingprice)
																																from Bidding b
																																where o.productid = b.productid) as biddingprice
from Object o left outer join productPhoto pp on o.productid=pp.productid
where o.productid = '$url'");
    $content = array();
    while ($row = $result->fetch()) {
        $ad = array($row['Title'], $row['description'], $row['Categories'], $row['filename'], $row['biddingprice'], $row['seller'], $row['productid'], $row['durationendDay']);
        $content[] = $ad;
    }
    return $content;
    checkAd();
}

$value = getAd();
function checkBod()
{
    global $errors;
    $errors['bod'] = ($_POST['bod'] == "") && ($_POST['bod'] < selectHighestBid()) ? "Vul aub een geldig bod in" : '';
}

function checkAd(){
    global $pdo;

    $stmt = $pdo->prepare("SELECT durationendTime, durationendDay FROM Object WHERE productid = ?");
    $stmt->execute([$_GET['link']]);
    $dataAd = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentDate = date("Y-m-d H:i:s");
    $dateAuction = $dataAd['durationendDay'] . ' ' . $dataAd['durationendTime'];
    if (strtotime($currentDate) <= strtotime($dateAuction)) {
        $stmt = $pdo->prepare("UPDATE Object SET auctionClosed = 0 WHERE productid = ?");
        $stmt->execute([$_GET['link']]);
    } else {
        $stmt = $pdo->prepare("UPDATE Object SET auctionClosed = 1 WHERE productid = ?");
        $stmt->execute([$_GET['link']]);
    }
}


function checkNoErrorBod()
{
    global $errors;
    if (!empty($errors['bod'])) return false;
    return true;
}

function checkIfBetweenPriceRange()
{
    if (!empty(selectHighestBid())) {
        if ((float)selectHighestBid() >= 1.00 && (float)selectHighestBid() <= 49.99 && (float)$_POST['bod'] >= (float)selectHighestBid() + 0.50) {
            return array(true, 1.00, 49.99, 0.50);
        } elseif ((float)selectHighestBid() >= 50 && (float)selectHighestBid() <= 499.99 && (float)$_POST['bod'] >= (float)selectHighestBid() + 1.00) {
            return array(true, 49.99, 499.99, 1.00);
        } elseif ((float)selectHighestBid() >= 500 && (float)selectHighestBid() <= 999.99 && (float)$_POST['bod'] >= (float)selectHighestBid() + 5.00) {
            return array(true, 500, 999.99, 5.00);
        } elseif ((float)selectHighestBid() >= 1000 && (float)selectHighestBid() <= 4999.99 && (float)$_POST['bod'] >= (float)selectHighestBid() + 10.00) {
            return array(true, 1000, 4999.99, 10.00);
        } elseif ((float)selectHighestBid() >= 5000 && (float)$_POST['bod'] > (float)selectHighestBid() + 50.00) {
            return array(true, 5000, 9999, 50.0);
        } else {
            return false;
        }
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $errors;
    if (isset($_POST['submit'])) {
        checkBod();
    }
    $startPrice = selectStartPrice();
    if (isset($startPrice) && checkNoErrorBod() && (int)$_POST['bod'] > (int)selectStartPrice() && checkIfBetweenPriceRange()) {
        saveBid();
    } else if (!isset($startPrice) && checkNoErrorBod() && (int)$_POST['bod'] > (int)selectHighestBid() && checkIfBetweenPriceRange()) {
        saveBid();
    } elseif (!checkIfBetweenPriceRange()) {
        if ((float)selectHighestBid() >= 1.00 && (float)selectHighestBid() <= 49.99) {
            $errors['bod'] = "Uw bod moet hoger € 0.50 hoger zijn dan bod nummer 1.";
        } elseif ((float)selectHighestBid() >= 49.99 && (float)selectHighestBid() <= 499.99) {
            $errors['bod'] = "Uw bod moet hoger € 1.00 hoger zijn dan bod nummer 1.";
        } elseif ((float)selectHighestBid() >= 500 && (float)selectHighestBid() <= 999.99) {
            $errors['bod'] = "Uw bod moet hoger € 5.00 hoger zijn dan bod nummer 1.";
        } elseif ((float)selectHighestBid() >= 1000 && (float)selectHighestBid() <= 4999.99) {
            $errors['bod'] = "Uw bod moet hoger € 10.00 hoger zijn dan bod nummer 1.";
        } elseif ((float)selectHighestBid() >= 5000) {
            $errors['bod'] = "Uw bod moet hoger € 50.00 hoger zijn dan bod nummer 1.";
        }

    } else if (!empty(selectHighestBid()) && (int)$_POST['bod'] < (int)selectHighestBid()) {
        $errors['bod'] = "Vul aub een hoger bod in dan het huidige bod.";
    } else if ((int)$_POST['bod'] > 9999.99) {
        $errors['bod'] = "Het bod wat u heeft geplaatst is te hoog. Het bod mag maximaal € 9999,99 zijn.";
    } else if (empty(selectHighestBid()) && (int)$_POST['bod'] < (int)selectStartPrice()) {
        $errors['bod'] = "Bod moet hoger zijn dan de startprijs.";
    }
}

function selectStartPrice()
{
    global $url, $pdo;
    $result = $pdo->query("select startprice from Object where productid like '%$url%'");
    $row = $result->fetch();
    return $row['startprice'];
}

function getBids()
{
    global $url;
    global $pdo;
    $result = $pdo->query("select top 5 biddingprice as biddingprice, [user], productid, email from Bidding inner join Users on bidding.[user] = Users.username where productid like '%$url%' order by biddingprice DESC");
    $bids = array();
    while ($row = $result->fetch()) {
        $bid = array($row['biddingprice'], $row['user'], $row['email']);
        $bids[] = $bid;
    }
    return $bids;
}

function selectHighestBid()
{
    global $url, $pdo;
    $result = $pdo->query("select max(biddingprice) as biddingprice from Bidding where productid = '$url'");
    $row = $result->fetch();
    return $row['biddingprice'];
}

function getPhotos()
{
    global $url;
    global $pdo;
    $result = $pdo->query("select filename from productPhoto where productid like '%$url%'");
    $photos = array();
    while ($row = $result->fetch()) {
        $photo = array($row['filename']);
        $photos[] = $photo;
    }
    return $photos;
}

function getlowerbid($prid, $pdo)
{
    $sqlstmt = <<<SQL
SELECT email FROM Users WHERE username = (SELECT [user] FROM Bidding WHERE productid = ? and biddingprice = ?)
SQL;
    $getmail = $pdo->prepare($sqlstmt);
    $getmail->execute([$prid, selectHighestBid()]);
    $email = $getmail->fetchColumn();
    return $email;
}

function saveBid()
{
    global $pdo, $url, $app_url;
    $email = getlowerbid($url, $pdo);
    if ($email) {
        $subject = "U bent overboden!";
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>EenmaalAndermaal overboden notificatie</title>
    <style type="text/css">

        * {
            margin:0;
            padding:0;
            font-family: Helvetica, Arial, sans-serif;
        }

        img {
            max-width: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        .image-fix {
            display:block;
        }

        .collapse {
            margin:0;
            padding:0;
        }

        body {
            -webkit-font-smoothing:antialiased;
            -webkit-text-size-adjust:none;
            width: 100%!important;
            height: 100%;
            text-align: center;
            color: #747474;
            background-color: #ffffff;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: Helvetica, Arial, sans-serif;
            line-height: 1.1;
        }

        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {
            font-size: 60%;
            line-height: 0;
            text-transform: none;
        }

        h1 {
            font-weight:200;
            font-size: 44px;
        }

        h2 {
            font-weight:200;
            font-size: 32px;
            margin-bottom: 14px;
        }

        h3 {
            font-weight:500;
            font-size: 27px;
        }

        h4 {
            font-weight:500;
            font-size: 23px;
        }

        h5 {
            font-weight:900;
            font-size: 17px;
        }

        h6 {
            font-weight:900;
            font-size: 14px;
            text-transform: uppercase;
        }

        .collapse {
            margin:0!important;
        }

        td, div {
            font-family: Helvetica, Arial, sans-serif;
            text-align: center;
        }

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            font-size:14px;
            line-height:1.6;
        }

        p.lead {
            font-size:17px;
        }

        p.last {
            margin-bottom:0px;
        }

        ul li {
            margin-left:5px;
            list-style-position: inside;
        }

        a {
            color: #747474;
            text-decoration: none;
        }

        a img {
            border: none;
        }

        .head-wrap {
            width: 100%;
            margin: 0 auto;
            background-color: #f9f8f8;
            border-bottom: 1px solid #d8d8d8;
        }

        .head-wrap * {
            margin: 0;
            padding: 0;
        }

        .header-background {
            background: repeat-x url(https://www.filepicker.io/api/file/wUGKTIOZTDqV2oJx5NCh) left bottom;
        }

        .header {
            height: 42px;
        }

        .header .content {
            padding: 0;
        }

        .header .brand {
            font-size: 16px;
            line-height: 42px;
            font-weight: bold;
        }

        .header .brand a {
            color: #464646;
        }

        .body-wrap {
            width: 505px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .soapbox .soapbox-title {
            font-size: 30px;
            color: #464646;
            padding-top: 25px;
            padding-bottom: 28px;
        }

        .content .status-container.single .status-padding {
            width: 80px;
        }

        .content .status {
            width: 90%;
        }

        .content .status-container.single .status {
            width: 300px;
        }

        .status {
            border-collapse: collapse;
            margin-left: 15px;
            color: #656565;
        }

        .status .status-cell {
            border: 1px solid #b3b3b3;
            height: 50px;
        }

        .status .status-cell.success,
        .status .status-cell.active {
            height: 65px;
        }

        .status .status-cell.success {
            background: #f2ffeb;
            color: #51da42;
        }

        .status .status-cell.success .status-title {
            font-size: 15px;
        }

        .status .status-cell.active {
            background: #fffde0;
            width: 135px;
        }

        .status .status-title {
            font-size: 16px;
            font-weight: bold;
            line-height: 23px;
        }

        .status .status-image {
            vertical-align: text-bottom;
        }

        .body .body-padded,
        .body .body-padding {
            padding-top: 34px;
        }

        .body .body-padding {
            width: 41px;
        }

        .body-padded,
        .body-title {
            text-align: left;
        }

        .body .body-title {
            font-weight: bold;
            font-size: 17px;
            color: #464646;
        }

        .body .body-text .body-text-cell {
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
            padding: 9px 0 17px;
        }

        .body .body-signature-block .body-signature-cell {
            padding: 25px 0 30px;
            text-align: left;
        }

        .body .body-signature {
            font-family: "Comic Sans MS", Textile, cursive;
            font-weight: bold;
        }

        .footer-wrap {
            width: 100%;
            margin: 0 auto;
            clear: both !important;
            background-color: #e5e5e5;
            border-top: 1px solid #b3b3b3;
            font-size: 12px;
            color: #656565;
            line-height: 30px;
        }

        .footer-wrap .container {
            padding: 14px 0;
        }

        .footer-wrap .container .content {
            padding: 0;
        }

        .footer-wrap .container .footer-lead {
            font-size: 14px;
        }

        .footer-wrap .container .footer-lead a {
            font-size: 14px;
            font-weight: bold;
            color: #535353;
        }

        .footer-wrap .container a {
            font-size: 12px;
            color: #656565;
        }

        .footer-wrap .container a.last {
            margin-right: 0;
        }

        .footer-wrap .footer-group {
            display: inline-block;
        }

        .container {
            display: block !important;
            max-width: 505px !important;
            clear: both !important;
        }

        .content {
            padding: 0;
            max-width: 505px;
            margin: 0 auto;
            display: block;
        }

        .content table {
            width: 100%;
        }


        .clear {
            display: block;
            clear: both;
        }

        table.full-width-gmail-android {
            width: 100% !important;
        }

    </style>

    <style type="text/css" media="only screen">

        @media only screen {

            table[class*="head-wrap"],
            table[class*="body-wrap"],
            table[class*="footer-wrap"] {
                width: 100% !important;
            }

            td[class*="container"] {
                margin: 0 auto !important;
            }

        }

        @media only screen and (max-width: 505px) {

            *[class*="w320"] {
                width: 320px !important;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active {
                display: block !important;
                width: auto !important;
            }

            table[class="status-container single"] table[class="status"] {
                width: 270px !important;
                margin-left: 0;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active,
            table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 65px !important;
                font-size: 18px !important;
            }

            table[class="status-container single"] table[class="status"] td[class*="status-cell"],
            table[class="status-container single"] table[class="status"] td[class*="status-cell"].active,
            table[class="status-container single"] table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 51px !important;
            }

            table[class="status"] td[class*="status-cell"].active [class*="status-title"] {
                display: inline !important;
            }

        }
    </style>
</head>

<body bgcolor="#ffffff">

<div align="center">
    <table class="head-wrap w320 full-width-gmail-android" bgcolor="#f9f8f8" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td background="https://www.filepicker.io/api/file/UOesoVZTFObSHCgUDygC" bgcolor="#ffffff" width="100%" height="8" valign="top">
                <div height="8">
                </div>
            </td>
        </tr>
        <tr class="header-background">
            <td class="header container" align="center">
                <div class="content">
            <span class="brand">
              <a href="#">
                EenmaalAndermaal
              </a>
            </span>
                </div>
            </td>
        </tr>
    </table>

    <table class="body-wrap w320">
        <tr>
            <td></td>
            <td class="container">
                <div class="content">
                    <table cellspacing="0">
                        <tr>
                            <td>
                                <table class="soapbox">
                                    <tr>
                                        <td class="soapbox-title">U bent overboden!</td>
                                    </tr>
                                </table>                     
                                <table class="body">
                                    <tr>
                                        <td class="body-padding"></td>
                                        <td class="body-padded">
                                            <!--<div class="body-title">BODY TITLE</div>-->
                                            <table class="body-text">
                                                <tr>
                                                    <td class="body-text-cell">
                                                        Oh nee! Uw laatste bod op <strong>' . getad()[0][0] . '</strong> is overboden door <strong>' . $_SESSION['username'] . '</strong>, klik op de link hier onder om het bod te bekijken.
                                                    </td>
                                                </tr>
                                            </table>
                                          <table class="status-container single">
                                    <tr>
                                        <td class="status-padding"></td>
                                        <td>
                                            <table class="status" bgcolor="#fffeea" cellspacing="0">
                                                <tr>
                                                    <td class="status-cell">
                                                        <a href="' . $app_url . '/views/public/productpage.php?link=' . $_GET['link'] . '">' . getad()[0][0] . '</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="status-padding"></td>
                                    </tr>
                                </table>
                        
                                            <table class="body-signature-block">
                                                <tr>
                                                    <td class="body-signature-cell">
                                                        <p>Hartelijk dank,</p>
                                                        <p class="body-signature"><img src="https://securehub.eu/dl/iproject/logo-ea-groot-donker.png" style="max-height: 70px" alt="EenmaalAndermaal Logo"></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="body-padding"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td></td>
        </tr>
    </table>

    <table class="footer-wrap w320 full-width-gmail-android" bgcolor="#578CA9" style="background-color:#578CA9; color:white;">
        <tr>
            <td class="container">
                <div class="content footer-lead">
                    Indien u nog vragen heeft, kunt u <a href="mailto:info@iproject42.icasites.nl">contact</a> met ons opnemen.
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>';
        $headers = 'From: noreply@iproject42.icasites.nl' . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($email, $subject, $message, $headers);
    }

    $stmt = "INSERT INTO Bidding(productid, biddingprice, [user], biddingday, biddingtime)
             VALUES (?, ?, ?, ?, ?)";
    $addBid = $pdo->prepare($stmt);
    if ($addBid->execute([$url, $_POST['bod'], $_SESSION['username'], date("Y-m-d"), date("H:i:s")])) {
        mailUser();
        //header("Refresh:0");
    }
}

function mailUser()
{
    global $app_url;
    $subject = "EenmaalAndermaal: Uw bieding is succesvol geplaatst.";
    $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>EenmaalAndermaal bieding</title>
    <style type="text/css">

        * {
            margin:0;
            padding:0;
            font-family: Helvetica, Arial, sans-serif;
        }

        img {
            max-width: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        .image-fix {
            display:block;
        }

        .collapse {
            margin:0;
            padding:0;
        }

        body {
            -webkit-font-smoothing:antialiased;
            -webkit-text-size-adjust:none;
            width: 100%!important;
            height: 100%;
            text-align: center;
            color: #747474;
            background-color: #ffffff;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: Helvetica, Arial, sans-serif;
            line-height: 1.1;
        }

        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {
            font-size: 60%;
            line-height: 0;
            text-transform: none;
        }

        h1 {
            font-weight:200;
            font-size: 44px;
        }

        h2 {
            font-weight:200;
            font-size: 32px;
            margin-bottom: 14px;
        }

        h3 {
            font-weight:500;
            font-size: 27px;
        }

        h4 {
            font-weight:500;
            font-size: 23px;
        }

        h5 {
            font-weight:900;
            font-size: 17px;
        }

        h6 {
            font-weight:900;
            font-size: 14px;
            text-transform: uppercase;
        }

        .collapse {
            margin:0!important;
        }

        td, div {
            font-family: Helvetica, Arial, sans-serif;
            text-align: center;
        }

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            font-size:14px;
            line-height:1.6;
        }

        p.lead {
            font-size:17px;
        }

        p.last {
            margin-bottom:0px;
        }

        ul li {
            margin-left:5px;
            list-style-position: inside;
        }

        a {
            color: #747474;
            text-decoration: none;
        }

        a img {
            border: none;
        }

        .head-wrap {
            width: 100%;
            margin: 0 auto;
            background-color: #f9f8f8;
            border-bottom: 1px solid #d8d8d8;
        }

        .head-wrap * {
            margin: 0;
            padding: 0;
        }

        .header-background {
            background: repeat-x url(https://www.filepicker.io/api/file/wUGKTIOZTDqV2oJx5NCh) left bottom;
        }

        .header {
            height: 42px;
        }

        .header .content {
            padding: 0;
        }

        .header .brand {
            font-size: 16px;
            line-height: 42px;
            font-weight: bold;
        }

        .header .brand a {
            color: #464646;
        }

        .body-wrap {
            width: 505px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .soapbox .soapbox-title {
            font-size: 30px;
            color: #464646;
            padding-top: 25px;
            padding-bottom: 28px;
        }

        .content .status-container.single .status-padding {
            width: 80px;
        }

        .content .status {
            width: 90%;
        }

        .content .status-container.single .status {
            width: 300px;
        }

        .status {
            border-collapse: collapse;
            margin-left: 15px;
            color: #656565;
        }

        .status .status-cell {
            border: 1px solid #b3b3b3;
            height: 50px;
        }

        .status .status-cell.success,
        .status .status-cell.active {
            height: 65px;
        }

        .status .status-cell.success {
            background: #f2ffeb;
            color: #51da42;
        }

        .status .status-cell.success .status-title {
            font-size: 15px;
        }

        .status .status-cell.active {
            background: #fffde0;
            width: 135px;
        }

        .status .status-title {
            font-size: 16px;
            font-weight: bold;
            line-height: 23px;
        }

        .status .status-image {
            vertical-align: text-bottom;
        }

        .body .body-padded,
        .body .body-padding {
            padding-top: 34px;
        }

        .body .body-padding {
            width: 41px;
        }

        .body-padded,
        .body-title {
            text-align: left;
        }

        .body .body-title {
            font-weight: bold;
            font-size: 17px;
            color: #464646;
        }

        .body .body-text .body-text-cell {
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
            padding: 9px 0 17px;
        }

        .body .body-signature-block .body-signature-cell {
            padding: 25px 0 30px;
            text-align: left;
        }

        .body .body-signature {
            font-family: "Comic Sans MS", Textile, cursive;
            font-weight: bold;
        }

        .footer-wrap {
            width: 100%;
            margin: 0 auto;
            clear: both !important;
            background-color: #e5e5e5;
            border-top: 1px solid #b3b3b3;
            font-size: 12px;
            color: #656565;
            line-height: 30px;
        }

        .footer-wrap .container {
            padding: 14px 0;
        }

        .footer-wrap .container .content {
            padding: 0;
        }

        .footer-wrap .container .footer-lead {
            font-size: 14px;
        }

        .footer-wrap .container .footer-lead a {
            font-size: 14px;
            font-weight: bold;
            color: #535353;
        }

        .footer-wrap .container a {
            font-size: 12px;
            color: #656565;
        }

        .footer-wrap .container a.last {
            margin-right: 0;
        }

        .footer-wrap .footer-group {
            display: inline-block;
        }

        .container {
            display: block !important;
            max-width: 505px !important;
            clear: both !important;
        }

        .content {
            padding: 0;
            max-width: 505px;
            margin: 0 auto;
            display: block;
        }

        .content table {
            width: 100%;
        }


        .clear {
            display: block;
            clear: both;
        }

        table.full-width-gmail-android {
            width: 100% !important;
        }

    </style>

    <style type="text/css" media="only screen">

        @media only screen {

            table[class*="head-wrap"],
            table[class*="body-wrap"],
            table[class*="footer-wrap"] {
                width: 100% !important;
            }

            td[class*="container"] {
                margin: 0 auto !important;
            }

        }

        @media only screen and (max-width: 505px) {

            *[class*="w320"] {
                width: 320px !important;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active {
                display: block !important;
                width: auto !important;
            }

            table[class="status-container single"] table[class="status"] {
                width: 270px !important;
                margin-left: 0;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active,
            table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 65px !important;
                font-size: 18px !important;
            }

            table[class="status-container single"] table[class="status"] td[class*="status-cell"],
            table[class="status-container single"] table[class="status"] td[class*="status-cell"].active,
            table[class="status-container single"] table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 51px !important;
            }

            table[class="status"] td[class*="status-cell"].active [class*="status-title"] {
                display: inline !important;
            }

        }
    </style>
</head>

<body bgcolor="#ffffff">

<div align="center">
    <table class="head-wrap w320 full-width-gmail-android" bgcolor="#f9f8f8" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td background="https://www.filepicker.io/api/file/UOesoVZTFObSHCgUDygC" bgcolor="#ffffff" width="100%" height="8" valign="top">
                <!--[if gte mso 9]>
                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:8px;">
                    <v:fill type="tile" src="https://www.filepicker.io/api/file/UOesoVZTFObSHCgUDygC" color="#ffffff" />
                    <v:textbox inset="0,0,0,0">
                <![endif]-->
                <div height="8">
                </div>
                <!--[if gte mso 9]>
                </v:textbox>
                </v:rect>
                <![endif]-->
            </td>
        </tr>
        <tr class="header-background">
            <td class="header container" align="center">
                <div class="content">
            <span class="brand">
              <a href="#">
                EenmaalAndermaal
              </a>
            </span>
                </div>
            </td>
        </tr>
    </table>

    <table class="body-wrap w320">
        <tr>
            <td></td>
            <td class="container">
                <div class="content">
                    <table cellspacing="0">
                        <tr>
                            <td>
                                <table class="soapbox">
                                    <tr>
                                        <td class="soapbox-title">Uw bod is succesvol opgeslagen!</td>
                                    </tr>
                                </table>                     
                                <table class="body">
                                    <tr>
                                        <td class="body-padding"></td>
                                        <td class="body-padded">
                                            <table class="body-text">
                                                
                                            </table>
                                          <table class="status-container single">
                                          <tr>
                                        <td class="status-padding"></td>
                                        <td>
                                            <table class="status" bgcolor="#fffeea" cellspacing="0">
                                                <tr>
                                                    <td class="status-cell">
                                                    
                                                        U heeft <strong>€' . getAd()[0][4] . ' </strong> geboden op: <strong><a href="' . $app_url . '/views/public/productpage.php?link=' . $_GET['link'] . '">' . getAd()[0][0] . '</a></strong>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="status-padding"></td>
                                    </tr>
                                </table>
                        
                                            <table class="body-signature-block">
                                                <tr>
                                                    <td class="body-signature-cell">
                                                        <p>Hartelijk dank,</p>
                                                        <p class="body-signature"><img src="https://securehub.eu/dl/iproject/logo-ea-groot-donker.png" style="max-height: 70px" alt="EenmaalAndermaal Logo"></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="body-padding"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td></td>
        </tr>
    </table>

    <table class="footer-wrap w320 full-width-gmail-android" bgcolor="#578CA9" style="background-color:#578CA9; color:white;">
        <tr>
            <td class="container">
                <div class="content footer-lead">
                    Indien u nog vragen heeft, kunt u <a href="mailto:info@iproject42.icasites.nl">contact</a> met ons opnemen.
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>';
    $headers = 'From: noreply@iproject42.icasites.nl' . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    global $user;
    $userMail = $user['email'];
    mail($userMail, $subject, $message, $headers);

}

?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators"
                ">
                <?php
                $getPhotos = getPhotos();
                foreach ($getPhotos as $i => $value) {
                    ?>
                    <li class="<?php if ($i == 0) {
                        echo "active";
                    } else {
                        echo "";
                    } ?>" data-target="#carourselExampleIndicators" data-slide-to="<?php echo $i ?>"></li>
                    <?php
                }
                ?>

                </ol>
                <div class="carousel-inner" role="listbox">
                    <?php
                    foreach ($getPhotos as $i => $value) {
                        ?>
                        <div class="carousel-item <?php if ($i == 0) {
                            echo "active";
                        } ?>">
                            <?php
                            if (substr($value[0], 0, 3) === "dt_") {
                                $picsource = "http://iproject42.icasites.nl/pics/";
                            } else {
                                $picsource = "http://iproject42.icasites.nl/uploads/";
                            }
                            ?>
                            <img class="d-block img-fluid size mx-auto" src="<?= $picsource ?>/<?php echo $value[0] ?>"
                                 style="max-height: 400px; width: auto;">
                        </div>

                        <?php
                    }

                    ?>
                </div>
                <?php if (count(getPhotos()) > 1) {
                    ?>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                       data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                       data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    <?php
                }

                ?>

                <div class="img-thumbnail">
                    <div class="figure-caption">
                        <h4 class="pull-right"><?php
                            if (empty(selectHighestBid())) {
                                echo 'Begin met bieden vanaf: € ' . selectStartPrice();
                            } else {
                                echo 'Huidig bod: € ' . selectHighestBid();
                            }
                            ?></h4>
                        <h4><?php print '<strong>Verkoper: </strong> ' . getAd()[0][5] ?></h4>
                        <h4><?php print '<strong>Titel: </strong></h4><h5>' . getAd()[0][0] . '</h5>'; ?>
                            <?php print '<br><h5><strong>Beschrijving: </strong></h5>' . '<h8>' . getAd()[0][1] . '</h8>' ?>
                            <h4>
                                <?php
                                print '<h5>Einddatum: ' . getAd()[0][7] . '</h5>';
                                ?>
                            </h4>
                    </div>
                </div>
            </div>

            <?php
            global $pdo;
            $stmt = $pdo->prepare("SELECT auctionClosed FROM Object WHERE productid = ?");
            $stmt->execute([$_GET['link']]);
            $dataAdClosed = $stmt->fetchColumn();

            if ($dataAdClosed == 1) {
                print '<br>
                            <div class="alert alert-danger"><strong>Oei!</strong> Deze veiling is gesloten, hierdoor kunt u geen biedingen meer plaatsen</div>';
            } else if ($dataAdClosed == 0) {
                if (isset($_SESSION['username']) AND $_SESSION['username'] == getAd()[0][5]) {
                    ?>
                    <form action="<?= $app_url ?>/views/account/update-advertisement.php" method="post">
                        <button class="btn btn-default btn-sm" name="changeid"
                                value="<?= getAd()[0][6] ?>"><i
                                    class="fa fa-wrench"
                                    style="width: 16px; height: 16px;"></i></button>
                    </form>
                    <?php

                } else if (isset($_SESSION['username']) && $_SESSION['username'] != getAd()[0][5] || $user['admin'] == 1) {
                    ?>

                    <div class="well">
                        <div class="text-right">
                            <button onclick="showInput()" name="paymentBtn" id="paymentBtn"
                                    class="btn btn-success btn-lg">Bied nu!
                            </button>
                        </div>
                    </div>
                    <form method="post">
                <div id="showInput"
                     style="display: none" <?php
                if (!empty($errors['bod'])) {
                    echo "class=\"form-group row has-danger\"";
                } else if (isset($_POST['bod']) && $_POST['bod'] < selectHighestBid()) {
                    echo "class=\"form-group row has-danger\"";
                } else {
                    echo "class=form-group row";
                }
                //  print((!empty($errors['bod'])) ? 'class="form-group row has-danger"' : 'class="form-group row"'); ?>>
                    <label class="col-2 col-form-label"></label>
                    <div class="input-inline col-10">

                    <input placeholder="€ 0,00" id="bod" name="bod" type="number"
                           step="0.01"
                    "
                    class="form-control">
                    <div class="form-control-feedback"><?php global $errors;
                        echo $errors['bod'];

                        ?></div>

                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] != getAd()[0][5]) {
                        ?>
                        <button name="submit" id="submit" class="btn btn-lg btn-secondary">Plaats bod!</button>
                        <?php
                        if (!empty(selectHighestBid())) {
                            if ((float)selectHighestBid() >= 1.00 && (float)selectHighestBid() <= 49.99) {
                                echo "Uw bod moet minimaal € 0.50 hoger zijn dan bod nummer 1.";
                            } elseif ((float)selectHighestBid() >= 49.99 && (float)selectHighestBid() <= 499.99) {
                                echo "Uw bod moet minimaal € 1.00 hoger zijn dan bod nummer 1.";
                            } elseif ((float)selectHighestBid() >= 500 && (float)selectHighestBid() <= 999.99) {
                                echo "Uw bod moet minimaal € 5.00 hoger zijn dan bod nummer 1.";
                            } elseif ((float)selectHighestBid() >= 1000 && (float)selectHighestBid() <= 4999.99) {
                                echo "Uw bod moet minimaal € 10.00 hoger zijn dan bod nummer 1.";
                            } elseif ((float)selectHighestBid() >= 5000) {
                                echo "Uw bod moet minimaal € 50.00 hoger zijn dan bod nummer 1.";
                            }
                        }
                        ?>
                        <hr style="width: auto;">

                        </div>

                        </div>
                        </form>
                        <?php
                    }
                } else if (isset($_SESSION['username']) && $_SESSION['username'] == getAd()[0][5]) {
                    ?>
                    <h4>U kunt niet op uw eigen product bieden!</h4>
                    <hr style="width: auto;">
                    <?php
                } else {
                    ?>
                    <h4>Meld u eerst aan om een bod te plaatsen!</h4>
                    <hr style="width: auto;">
                    <?php
                }
            } ?>

        </div>

        <div class="col-md-3">


            <div class="list-group">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Bod</th>
                        <?php
                        if (getAd()[0][5] == $_SESSION['username']) {
                            print '<th>Email</th>';
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $Bids = getBids();
                    $i = 0;
                    foreach ($Bids as $value) {
                        $i++;
                        echo "<tr>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . $value[1] . "</td>";
                        echo "<td>" . $value[0] . "</td>";
                        if (getAd()[0][5] == $_SESSION['username']) {
                            echo "<td>" . $value[2] . "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
</div>

<!-- /.container -->


<!-- /.container -->

<!-- jQuery -->


<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
//include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>

<script>

    var x = 1;
    var y = 0;
    function showInput() {
        if (x > y) {
            document.getElementById("showInput").style.display = 'block';
            x = -1;
        } else if (x < y) {
            document.getElementById("showInput").style.display = 'none';
            x = 1;
        }

    }
    <?php if($_SERVER['REQUEST_METHOD'] == "POST" && !checkNoErrorBod()){
    ?>
    document.getElementById("showInput").style.display = 'block';
    <?php
    }?>
</script>
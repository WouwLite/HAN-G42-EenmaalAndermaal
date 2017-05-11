<!-- /config/cdn.php -->

<?php

$cdn_local_url = '//';
$cdn_url = $app_url; // Default value

if ($cdn_enable == true) {
    $cdn_url = $cdn_remote_url;
} else {
    $cdn_url = $app_url;
}
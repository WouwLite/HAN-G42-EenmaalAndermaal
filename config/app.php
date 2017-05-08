<!-- /config/app.php -->

<?php
/* General */
// The title of the application
$title = 'EenmaalAndermaal';
$slogan = 'De wereld draait door!';

/* Environment & debugging */
// Environment stages: Development, Staging, Production
$environment = 'Development';
$debug = 'true';

// Image / style location
// Do you host your images on the locally or are they on an other server (like a CDN)?
// TRUE = Your images AND stylesheets are hosted on an external server (which functions as a Content Delivery Network)
// FALSE = Your images AND stylesheets are hosted on the local machine (/storage/OLD-css and /storage/images)
$cdn_enable = true;
$cdn_remote_url = 'https://cdn.wouwlite.eu/fletnix.nl/';


/*
 * DON'T EDIT DATA BELOW THIS LINE
 */

$cdn_local_url = '';
$cdn_url = '';

if ($cdn_enable == true) {
    $cdn_url = $cdn_remote_url;
} else {
    $cdn_url = '';
}

// Define which url path to choose
if ($pathtype = true) {
    $urlpath = $abspath;
} else {
    $urlpath = $relpath;
}
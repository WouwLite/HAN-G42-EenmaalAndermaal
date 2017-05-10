<!-- /config/app.php -->

<?php

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the title of the application. This value is used when the
    | website needs to place the application's name in a notification or
    | any other location as required by the application.
    */

    $title                      = 'EenmaalAndermaal';
    $slogan                     = 'De wereld draait door!';

    /*
    |--------------------------------------------------------------------------
    | Application Address
    |--------------------------------------------------------------------------
    |
    | This value determines the URL of the application. This is needed
    | for several functions within the application. Default value is
    | 'localhost'. Don't forget the first two slashes (or http://).
    */

    $app_url                      = '//localhost';

    /*
    |--------------------------------------------------------------------------
    | Application Environment & Debug
    |--------------------------------------------------------------------------
    |
    | This value determines the developmentstatus of the application. This
    | gives a visual aid when developing new features or debugging. The
    | different types of environment are: 'Development' and 'Production'
    */

    $debug                      = true;
    $environment                = 'Development';

    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    |
    | The application supports both SMTP and PHP Sendmail. Select a driver
    | below to specify which type of email you want to use.
    | If you select SMTP, fill in the required fields.
    */

    $mail_driver                = 'SMTP';

    $mail_host                  = 'smtp.gmail.com';
    $mail_port                  = '587';
    $mail_encryption            = 'TLS';
    $mail_username              = 'transacties@ea-veiling.nl';
    $mail_password              = 'SomeWeirdPassword';
    $mail_from                  = 'noreply@ea-veiling.nl';

    /*
    |--------------------------------------------------------------------------
    | Content Delivery Network
    |--------------------------------------------------------------------------
    |
    | By using an external domain for images and css, you can reduce the
    | load time on the server. This value determines if you want to
    | use a CDN instead of the local host.
    */

    $cdn_enable                 = false;
    $cdn_remote_url             = '//cdn.wouwlite.eu/icasites.nl/';


    /*
     * Includes
     */

    include($_SERVER['DOCUMENT_ROOT'] . '/config/mail.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/config/cdn.php');
//    include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
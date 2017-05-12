<?php
// Developer values. Easy and quick function for testing and debugging
$dev_visible    = '1'; // Is the dev-data visible on the pages. 0 = no, 1 = yes

// Do not change this variable
$status         = '';

// Dummy data
$testName       = 'Billy the Kid';
$testBiedingNo  = '4';
$testAdvertNo   = '2';


// helper functions
function getRealPOST()
{
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}
?>
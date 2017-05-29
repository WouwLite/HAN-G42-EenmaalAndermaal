<?php
// Redirect visitors to the default homepage.
// Visitors are not allowed to index the images folder
// This is an extra check to ensure it wont happen.
// Created with <3
header("Refresh:0; url=/views/public/splash.php");
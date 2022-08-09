<?php
/*
Lab6
Santiago Mesa
Sec 304
*/

//page to redirect user after ourchase is made
//start session to get variables
session_start();
//destroy active session and variables
session_destroy();
//redirect to main web now with a clear form
header("Location: ACMEpurchases.php");
?>
<?php

/*
 * TESTING CurlBack for nonauthentication sessions
 *
 */

include "./Source/Curl.php";

/* Test setting address through function and Printing */
$myCurl = new Curl("http://api.shoutpay.com/authorize",true);
$myCurl->echoAddress();

$myCurl->makeRequest();

$myCurl->setAddress("http://www.google.com");
$myCurl->makeRequest();

echo "\n";
echo "\n";

print_r($myCurl->returnSavedRequests());


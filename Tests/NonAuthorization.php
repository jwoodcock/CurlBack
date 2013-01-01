<?php

/*
 * TESTING CurlBack for nonauthentication sessions
 *
 */

include "./Source/Curl.php";

/* Test setting address through function and Printing */
$addressTest = new Curl();
$addressTest->setAddress("http://www.yahoo.com");
$addressTest->echoAddress();

/* Test saving of multiple requests */
$myCurl = new Curl("http://www.facebook.com",true);
echo "\n";
$myCurl->echoAddress();

$myCurl->makeRequest();

$myCurl->setAddress("http://www.google.com");
$myCurl->makeRequest();

echo "\n";
echo "\n";

//print_r($myCurl->returnSavedRequests());

/* Test looking up status code */

echo "\n";
echo "\n";

//print_r($myCurl->lookupHttpCode());
echo "\n";
//print_r($myCurl->lookupHttpCode($myCurl->pastResponses[0]['http_code']));
echo "\n";

/* Test setting authorization */

echo "\n";
echo "\n";

$myCurl->setBasicAuth('user','somepass');

//print_r($myCurl);

echo "\n";

$myCurl->replayRequest(1);
//print_r($myCurl);

/* Print list of request */

print_r($myCurl->returnRequestList());
print_r($myCurl->returnRequestListWithTimes());

$myCurl->resetStoredResponses();

print_r($myCurl->returnRequestList());

echo "\n";

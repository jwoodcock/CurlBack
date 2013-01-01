cURLlack
========
Simple object wrapper for cURL

cURLBack is a simple cURL wrapper that allows storing of past requests for 
furture use without having to re-request a resource. cURLBack also provides a 
simple OOP interface for making and managing these requests. 

QUICK START
-----------

Using cURLBack is simple. Here's a simple example for making a request and managing
the response. 

<code>
$myRequest = new Curl('http://www.google.com');
$myRequest->makeRequest();
print_r($myRequest->getResponse());
</code>

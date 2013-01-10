cURLBack
========
Simple object wrapper for cURL

cURLBack is a simple cURL wrapper that allows storing of past requests for 
furture use without having to re-request a resource. cURLBack also provides a 
simple OOP interface for making and managing these requests. 

QUICK START
-----------

Using cURLBack is simple. Here's a simple example for making a request and printing 
the response. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com');
        $myRequest->makeRequest();
        print_r($myRequest->getResponse());
    </code>
</pre>

You can easily add GET variables one by one or via an array.

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com');
        $myRequest->setGetValue("variable","value");
        $myRequest->makeRequest();
        print_r($myRequest->getResponse());
    </code>
</pre>

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com');
        $getValues = array(
            "variable" => "value",
            "variable2" => "value,
        );
        $myRequest->setGetValue($getValues);
        $myRequest->makeRequest();
        print_r($myRequest->getResponse());
    </code>
</pre>

The same goes for POST variables using the setPostValue() method. 

You have multiple ways to interact with the response information from a request.

returnResponse() gives just the response from a request. 
returnRequestInfo() gives you the information from the response as well as the response headers.
returnHttpCode() returns just the response HTTP Code

REQUEST METHOD
--------------

Like cURL, cURLBack is defaulted to use the GET method with requests but you may change
the method using the changeToPost(), changeToDelete(), changeToPut() methods. There
is also a changeToCustom() method for undocumented methods. 

If you send POST variables, like cURL, cURLBack will automatically switch it's method
to POST.

CONVENIENCE METHODS
-------------------

cURLBack also has 4 convenience methods that allow you to build and make a request
through one call. One for GET, POST, PUT and DELETE.

These look like the following: 

<pre>
    <code>
        $curl = new Curl;
        $curl->post('http://www.example.com', array(
            'foo' => 'bar'
        ));

        $curl->get('http://www.example.com', array(
            'foo' => 'bar'
        ));

        $curl->put('http://www.example.com', array(
            'foo' => 'bar'
        ));

        $curl->delete('http://www.example.com');
    </code>
</pre>

SAVING REQUESTS
---------------

cURLBack has a built in request container that will store the information for
each request you make. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->makeRequest();
        print_r($myRequest->returnSavedRequests());
    </code>
</pre>

The returnSavedRequests() method will return an object with all information about 
each requests along with the full response details. You can use the
returnRequestList() and returnRequestListWithTimes() for abbreviated list with or
without the request times.


ADDING AN ADDITIONAL REQUEST 
----------------------------

To add additional request to the same cURLBack object, simply add a new request
address and then make a new request. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->makeRequest();
        $myRequest->setAddress('http://www.yahoo.com');
        $myRequest->makeRequest();
    </code>
</pre>

Once you add a new request address, the previous request is moved to the pastResponses
property. To access any of it's information you will want to call it as such. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->makeRequest();
        $myRequest->setAddress('http://www.yahoo.com');
        $myRequest->makeRequest();

        $myRequest->pastResponses[0]['response'];
    </code>
</pre>


REPLAY A REQUEST
----------------

You can replay any previous saved request using the replayRequest() method. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->makeRequest();
        $myRequest->replayRequest(0);
    </code>
</pre>


CLEARING REQUEST HISTORY
------------------------

You can clear all request history using the resetStoredResponses() method. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->makeRequest();
        $myRequest->resetStoredResponses();
    </code>
</pre>


HEADERS & GLOBAL ACCOUNT TYPE, USER 
----------------------------------- 

You can also set HEADERs to the cURLBack object using the setHeader() method. 

<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->setHeader("Content-type","application/json");
        $myRequest->makeRequest();
    </code>
</pre>

By default, cURLBack sets a global accept type header of 'application/json' but 
you can change this at will using the setGlobalAccept() method. 
 
<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->setGlobalAccept('application/html');
        $myRequest->makeRequest();
    </code>
</pre>

Also, cURLBack is setup to store and send a USER header property for authentication
propurses. Simply set the $this->globalUser using the setGlobalUser() method.
 
<pre>
    <code>
        $myRequest = new Kite\CurlBack\Curl('http://www.google.com',true);
        $myRequest->setGlobalUser('10ksd93023ksdfj0230kj23lk');
        $myRequest->makeRequest();
    </code>
</pre>

PUBLIC PROPERTIES
=================

<pre>
$address = The active address to use when doing a request.
$getValues = An array holding all the get values of the active request.
$postValues = An array holding all the post values of the active request.
$method = The request method, POST, GET, PUT, DELTE or Custom
$storeRequests = Boolean for turning on or off request saving. 
$pastResponses = Array holding all the past request and respones if storeRequests = true
$globalAccept = The header ACCEPT type to use on all requests.
$globalUser = The header USER to use on all requests if provided. 
</pre>


FULL API LIST
=============

__construct($address, $storeRequests)
<pre>
The construct can take an valid URL as the $address and a boolean as the 
$storeRequests. Both are optional though. 
</pre>

setAddress($address)
<pre>
This method sets the active request URL.
</pre>

echoAddress()
<pre>
This method simply prints the current active address. 
</pre>

setGetValue($name, $value)
<pre>
The $name property can either be a string for the variable name or an array of 
variables and values. $value is only used if $name is a variable string and holds
the value of that variable. 
</pre>

setPostValue($name, $value)
<pre>
The $name property can either be a string for the variable name or an array of 
variables and values. $value is only used if $name is a variable string and holds
the value of that variable. 
</pre>

changeToPost()
<pre>
Changes $method to POST
</pre>

changeToPUT()
<pre>
Changes $method to PUT 
</pre>

changeToDelete()
<pre>
Changes $method to DELETE 
</pre>

changeToGet()
<pre>
Changes $method to GET 
</pre>

customMethod($method)
<pre>
The $method will change the request method to your custom method.
</pre>

setHeader($name, $value)
<pre>
The $name will be the name of the header while $value is the value of that header.
</pre>

removeHeader($num)
<pre>
This method will remove a header from the header list via the provided $num.
</pre>

returnHeaderCount()
<pre>
This method returns the total count of the provided headers. 
</pre>

setGlobalAccept($acceptType)
<pre>
This method changes the header ACCEPT type value. 
</pre>

setGlobalUser($user)
<pre>
This method sets the header USER property.
</pre>

returnResponse()
<pre>
This method will return the response from the last request.
</pre>

returnHttpCode()
<pre>
This method returns the HTTP code from the last request.
</pre>

returnResponseInfo()
<pre>
This method returns an array holding the response info and response headers. 
<code>
Array (
    "Response Info" : "info",
    "Response Headers" : "info",
) 
</code>
</pre>

returnSavedRequests()
<pre>
Returns an array of all the past responses. 
</pre>

setBasicAuth($un, $pw)
<pre>
    Sets basic authentication for the request with $un being the username and $pw being the password.
</pre>

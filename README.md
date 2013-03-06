cURLBack
========
Simple object wrapper for cURL

cURLBack is a simple cURL wrapper that allows storing of past requests for 
furture use without having to re-request a resource. cURLBack also provides a 
simple OOP interface for making and managing these requests. 

INSTALL
-------

You can use Composer to install CurlBack. 

<pre>
    <code>
        {
            "require": {
                "kite/curlback": "1.*"
            }
        }
    </code>
</pre>

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

BATCH REQUESTS
==============
cURLBack also supports batch processing of requests using an instance of the
main Curl object.  

<pre>
    <code>
        $requests = array(
            array(
                "address"=>"http://www.kitewebconsulting.com",
                "method"=>"GET",
            )
        );

        $curl = new Curl("", true);
        $batchRequest = new BatchHandler($requests, $curl);
        $batchRequest->processRequests();
</pre>

The BatchHandler has an expanded interface as well for multiple methods
of interactions. Here is a list of all available methods.

__construct($requests, $curl)
The $requests is an array holder requests arrays. See following section
for structure and requirements. 

The $curl is an instance of the $curl object to make request and store
responses. 

processBatchObj($requests)
If you want to add the requests after the BatchHandler object has been
instantiated you can use this method to pass in the request object. 

addRequest($request, $position)
This method allows you to add requests one at a time. The $request object
is an array with a single request array within it and the $position is 
optional but can be used to write over existing requests. 

clearRequests()
Empties all the stored requests. 

processRequests($current)
This is the main method for calling the requests and once called will 
loop through the request object until the last request is made. 

returnResponses()
Instead of having to call your $curl object to get all the responses
a shortcut has been setup so you can call them using the $batchHandler
object. 

$requests Object Structure
Here is a list of the fields you can pass and which are required or optional.
<pre>
    address     (string - required)
    method      (string - required)
    getValues   (key value array - optional)
    postValues  (key value array - optional)
    headers     (key value array - optional)
    accept      (string - optional) 
    user        (string - optional)
    un          (string - required if pw is set)
    pw          (string - required if un is set)

    So a request with full options would look like this:
    <code>
        $requests = array(
            array(
                "address"=>"http://www.kitportal.com",
                "method"=>"GET",
                "getValues"=>array(
                    "key"=>"val"
                )
                "postValues"=>array(
                    "key"=>"val"
                )
                "headers"=>array(
                    "key"=>"val"
                )
                "accept"=>"application/json",
                "user"=>"myUser",
                "un"=>"username",
                "pw"=>"password",
            )
        );
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
You can also just provide an array of headers in the name field to add bulk, just 
leave the value field empty. 
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

LICENSE
-------
Copyright (c) 2013, Kite, Inc 
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
 are permitted provided that the following conditions are met:

<ul>
<li>
Redistributions of source code must retain the above copyright notice, this 
list of conditions and the following disclaimer.
</li>
<li>
Redistributions in binary form must reproduce the above copyright notice, this
 list of conditions and the following disclaimer in the documentation and/or 
other materials provided with the distribution.
</li>
<li>
Neither the name of the <ORGANIZATION> nor the names of its contributors may 
be used to endorse or promote products derived from this software without 
specific prior written permission.</li>
</ul>

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR 
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON 
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

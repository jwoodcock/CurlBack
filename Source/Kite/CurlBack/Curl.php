<?php 

/*
 * CURLBack is a simple cURL wrapper that allows storing of past requests for 
 * furture use without having to re-request a resource and also provides a 
 * simple OOP interface for making these requests
 *
 * Author Jacques Woodcock
 * @jwoodcock on GitHub
 * https://github.com/jwoodcock/CurlBack
 *
 * Requirements: PHP cURL 
 *
 * Compatible: PHP 5.3, 5.4
 * 
 */

namespace Kite\CurlBack;

class Curl
{

    public $address = '';
    public $getValues = Array();
    public $postValues = Array();
    public $method = 'GET';
    public $storeRequests = false;
    public $pastResponses = Array();
    public $globalAccept = "application/json";
    public $globalUser = "";
    public $headers = Array();

    private $response = '';
    private $responseInfo = '';
    private $responseHeaders = '';
    private $httpCode = 0;

    private static $httpCodes = Array(
        "200" => '200 OK',
        "201" => '201 Created',
        "202" => '202 Accepted',
        "203" => '203 Non-Authoritative Information',
        "204" => '204 No Content',
        "205" => '205 Reset Content',
        "206" => '206 Partial Content',
        "207" => '207 Multi-Status (WebDAV; RFC 4918)',
        "208" => '208 Already Reported (WebDAV; RFC 5842)',
        "226" => '226 IM Used (RFC 3229)',
        "230" => '230 Authentication Successful',
        "300" => '300 Multiple Choices',
        "301" => '301 Moved Permanently',
        "302" => '302 Found',
        "303" => '303 See Other',
        "304" => '304 Not Modified',
        "305" => '305 Use Proxy',
        "306" => '306 Switch Proxy',
        "307" => '307 Temporary Redirect',
        "308" => '308 Permanent Redirect',
        "400" => '400 Bad Request',
        "401" => '401 Unauthorized',
        "402" => '402 Payment Required',
        "403" => '403 Forbidden',
        "404" => '404 Not Found',
        "405" => '405 Method Not Allowed',
        "406" => '406 Not Acceptable',
        "407" => '407 Proxy Authentication Required',
        "408" => '408 Request Timeout',
        "409" => '409 Conflict',
        "410" => '410 Gone',
        "411" => '411 Length Required',
        "412" => '412 Precondition Failed',
        "413" => '413 Request Entity Too Large',
        "414" => '414 Request-URI Too Long',
        "415" => '415 Unsupported Media Type',
        "416" => '416 Requested Range Not Satisfiable',
        "417" => '417 Expectation Failed',
        "418" => '418 I\'m a teapot',
        "420" => '420 Enhance Your Calm',
        "422" => '422 Unprocessable Entity',
        "423" => '423 Locked',
        "424" => '424 Failed Dependency',
        "425" => '425 Unordered Collection',
        "426" => '426 Upgrade Required',
        "428" => '428 Precondition Required',
        "429" => '429 Too Many Requests',
        "431" => '431 Request Header Fields Too Large',
        "444" => '444 No Response',
        "449" => '449 Retry With',
        "450" => '450 Blocked by Windows Parental Controls',
        "451" => '451 Unavailable For Legal Reasons',
        "494" => '494 Request Header Too Large',
        "495" => '495 Cert Error',
        "496" => '496 No Cert',
        "497" => '497 HTTP to HTTPS',
        "499" => '499 Client Closed Request',
        "500" => '500 Internal Server Error',
        "501" => '501 Not Implemented',
        "502" => '502 Bad Gateway',
        "503" => '503 Service has been shut down',
        "504" => '504 Gateway timeout',
        "505" => '505 HTTP Version Not Supported',
        "506" => '506 Variant Also Negotiates',
        "507" => '507 Insufficient Storage',
        "508" => '508 Loop Detected',
        "509" => '509 Bandwidth Limit Exceeded',
        "510" => '510 Not Extended',
        "511" => '511 Network Authentication Required',
        "531" => '531 Access Denied',
        "598" => '598 Network read timeout error',
        "599" => '599 Network connect timeout error',
    );

    public function __construct($address = "", $storeRequests = false)
    {
        if ($address) {
            $this->setAddress($address);
        }

        if ($storeRequests === true) {
            $this->storeRequests = true;
        } else {
            $this->storeRequests = false;
        }
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function echoAddress()
    {
        echo $this->address;
    }

    public function setGetValue($name, $value = "")
    {
        if (is_array($name) === false) {
            $this->getValues[$name] = $value;
        } else {
            foreach ($name as $key => $val) {
                $this->getValues[$key] = $val;
            }
        }
    }

    public function setPostValue($name, $value = "")
    {
        if (is_array($name) === false) {
            $this->postValues[$name] = $value;
        } else {
            foreach ($name as $key => $val) {
                $this->postValues[$key] = $val;
            }
        }
    }

    public function changeToPost()
    {
        $this->method = 'POST';
    }

    public function changeToPUT()
    {
        $this->method = 'PUT';
    }

    public function changeToDelete()
    {
        $this->method = 'DELETE';
    }

    public function changeToGet()
    {
        $this->method = 'GET';
    }

    public function customMethod($method)
    {
        $this->method = $method;
    }

    public function setHeader($name, $value)
    {
        $this->headers[] = $name . ": " .$value;
    }

    public function removeHeader($num)
    {
        unset($this->headers[$num]);
    }

    public function resetHeader($num, $name, $value)
    {
        $this->headers[$num] = $name . ": " . $value;
    }

    public function returnHeaderCount()
    {
        return count($this->headers);
    }

    public function setGlobalAccept($acceptType)
    {
        $this->globalAccept = $acceptType; 
    }

    public function setGlobalUser($user)
    {
        $this->globalUser = $user; 
    }

    public function returnResponse()
    {
        return $this->response;
    }

    public function returnHttpCode()
    {
        return $this->httpCode;
    }

    public function returnResponseInfo()
    {
        $returnValue = '';
        if ($this->responseInfo !== '' && $this->responseHeaders !== '') {
            $returnValue = array(
                "Response Info: "=>$this->responseInfo,
                "Response Headers: "=>$this->responseHeaders,
            );
        }
        return $returnValue;
    }

    private function saveRequest()
    {

        $next_request = count($this->pastResponses);
        $this->pastResponses[$next_request] = array(
            "address" => $this->address,
            "method" => $this->method,
            "request_time" => date("Y/m/d h:i:s"),
            "get_values" => $this->getValues,
            "post_values" => $this->postValues,
            "response" => $this->response,
            "response_info" => $this->responseInfo,
            "http_code" => $this->httpCode,
            "response_headers" => $this->responseHeaders
        );

        $this->getValues = array();
        $this->postValues = array();
        $this->httpCode = '';
        $this->responseHeaders = '';
        $this->responseInfo = '';
        $this->headers = array();
        
    }

    public function returnSavedRequests()
    {
        return $this->pastResponses;
    }

    public function setBasicAuth($un, $pw)
    {
        $this->setHeader("Authorization",$un.":".$pw);        
    }

    public function replayRequest($request)
    {
        if (is_numeric($request)) {

            $replayObj = $this->pastResponses[$request];
            $this->address = $replayObj['address'];
            $this->method = $replayObj['method'];
            $this->getValues = $replayObj['get_values'];
            $this->postValues = $replayObj['post_values'];

            return $this->makeRequest();
        }

    }

    public function returnRequestList()
    {
        $returnArray = Array();

        foreach ($this->pastResponses as $response) {
            $returnArray[] = $response['address']; 
        }

        return $returnArray;
    }

    public function returnRequestListWithTimes()
    {
        $returnArray = Array();

        foreach ($this->pastResponses as $response) {
            $returnArray[] = $response['request_time'] . " " .$response['address']; 
        }

        return $returnArray;
    }

    public function resetStoredResponses()
    {
        $this->pastResponses = Array();
    }

    public function makeRequest()
    {
        if ($this->address !== "") {
            // Initiate CURL object
            $ch = curl_init();
            $this->response = "";

            if ($this->globalAccept !== "") {
                $this->setHeader("Accept",$this->globalAccept);
            }

            if ($this->globalUser !== "") {
                $this->setHeader("User",$this->globalUser);
            }

            // Define CURL options
            curl_setopt($ch, CURLOPT_URL, $this->address);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     
            if ($this->method === 'POST') {
                $fields = $this->returnPostFieldsForRequest();
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
                curl_setopt($ch, CURLOPT_POST, 1);
            } else if ($this->method !== 'GET') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method); 
                if ($this->method === 'PUT') {
                    $fields = $this->returnPostFieldsForRequest();
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
                }
            }
     
            if (count($this->headers) > 0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            }

            // EXECUTE
            $result = curl_exec($ch);
            $this->responseInfo = curl_getinfo($ch);
            $this->responseHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);

            // CHECK FOR ERRORS
            if (curl_errno($ch)) {
                $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
            }
            $this->httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // CLOSE CONNECTION
            curl_close($ch);

            $this->response = $result;
            if ($this->storeRequests === true) {
                $this->saveRequest();
            }
        }
    }

    public function returnPostFieldsForRequest()
    {
        return http_build_query($this->postValues);
    }

    public function lookupHttpCode($lookUpCode = "")
    {
        if ($lookUpCode) {
            return self::$httpCodes[$lookUpCode];
        } else if ($this->httpCode) {
            return self::$httpCodes[$this->httpCode];
        } else {
            return 'no value provided';
        }
    }
}


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

class Curl
{

    public $address = '';
    public $getValues = Array();
    public $postValues = Array();
    public $method = 'GET';
    public $storeRequests = false;

    private $response = '';
    private $responseInfo = '';
    private $responseHeaders = '';
    private $headers = Array();
    private $httpCode = 0;

    private $pastResponses = Array();

    public function __construct($address = "", $storeRequests = "")
    {
        if ($address) {
            $this->setAddress($address);
        }

        if ($storeRequests === true) {
            $this->storeRequests = true;
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

    public function setGetValue($name, $value)
    {
        $this->getValues[$name] = $value;
    }

    public function setPostValue($name, $value)
    {
        $this->postValues[$name] = $value;
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
        $this->headers['num'] = $name . ": " . $value;
    }

    public function returnHeaderCount()
    {
        return count($this->headers);
    }

    public function returnResponse()
    {
        return $this->response;
    }

    public function returnHttpCode()
    {
        return $this->httpCode;
    }

    public function returnRequestInfo()
    {
        $returnValue = array(
            "Request Info: "=>$this->responseInfo,
            "Request Headers: "=>$this->responseHeaders,
        );
        return $returnValue;
    }

    private function saveRequest()
    {

        $this->pastResponses[$this->address] = array(
            "response" => $this->response,
            "response_info" => $this->responseInfo,
            "response_headers" => $this->responseHeaders
        );
        
    }

    public function returnSavedRequests()
    {
        return $this->pastResponses;
    }

    public function makeRequest()
    {
        // Initiate CURL object
        $ch = curl_init();

        // Define CURL options
        curl_setopt($ch, CURLOPT_URL, $this->address);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        if ($this->method === 'POST') {
            $fields_string = '';
            foreach($this->postValues as $key=>$value) { 
                $fields_string .= $key.'='.$value.'&';
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_POST, 1); // Set HTTP Method as POST
        }
 
        if (count($this->headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers); // Set headers
        }

        // EXECUTE
        $result = curl_exec($ch);
        $this->responseInfo = curl_getinfo($ch);
        $this->responseHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        // CHECK FOR ERRORS
        if (curl_errno($ch)) {
            $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
        } else {
            $this->httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch($this->httpCode){
                case 400:
                    $result = 'ERROR -> 400 Bad Request';
                    break;
                case 404:
                    $result = 'ERROR -> 404 Not Found';
                    break;
                case 500:
                    $result = 'ERROR -> 500 Internal Server Error';
                    break;
                case 503:
                    $result = 'ERROR -> 503 Service has been shut down';
                    break;
                case 504:
                    $result = 'ERROR -> 504 Gateway timeout';
                    break;
                default:
                    break;
            }
        }

        // CLOSE CONNECTION
        curl_close($ch);

        if ($this->storeRequests === true) {
            $this->saveRequest();
        }

        $this->response = $result;
    }
}


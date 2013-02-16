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

use Kite\CurlBack\CurlResponse;
use Kite\CurlBack\HttpCodes;
use Kite\CurlBack\MakeRequest;

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
    public $requestStorage = '';

    private $_response = '';
    private $_responseInfo = '';
    private $_responseHeaders = '';
    private $_httpCode = 0;
    private $_httpCodes = '';
    private $_request = '';

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

        $this->requestStorage = new CurlResponse();
        $this->_httpCodes = new HttpCodes();
        $this->_request = new MakeRequest();
    }

    public function get($address = null, $getValues = Array(), $value = '')
    {
        if ($address) {
            $this->setAddress($address);
        }

        $this->setGetValue($getValues, $value);
        $this->changeToGet();
        $this->makeRequest();
    }

    public function post($address = null, $postValues = Array(), $value = '')
    {
        if ($address) {
            $this->setAddress($address);
        }

        $this->setPostValue($postValues, $value);
        $this->changeToPost();
        $this->makeRequest();
    }

    public function put($address = null, $postValues = Array(), $value = '')
    {
        if ($address) {
            $this->setAddress($address);
        }

        $this->setPostValue($postValues, $value);
        $this->changeToPut();
        $this->makeRequest();
    }

    public function delete($address = null)
    {
        if ($address) {
            $this->setAddress($address);
        }

        $this->changeToDelete();
        $this->makeRequest();
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function echoAddress()
    {
        echo $this->address;
    }

    private function setValues($valueArray)
    {
        $values = array();
        foreach($valueArray as $key=>$value) {
            $values[$key] = $value;
        }
        return $values;
    }

    public function setGetValue($name, $value = "")
    {
        if (is_array($name) === false) {
            $this->getValues[$name] = $value;
        } else {
            $this->getValues = $this->setValues($name);
        }
    }

    public function setPostValue($name, $value = "")
    {
        if (is_array($name) === false) {
            $this->postValues[$name] = $value;
        } else {
            $this->postValues = $this->setValues($name);
        }
    }

    public function changeToPost()
    {
        $this->method = 'POST';
    }

    public function changeToPut()
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

    public function setHeader($name, $value = "")
    {
        if (is_array($name)) {
            $this->headers = array_merge($this->headers, $name);
        } else {
            $this->headers[$name] = $value;
        }
    }

    public function removeHeader($index)
    {
        if (is_integer($index)) {
            $keys = array_keys($this->headers);
            $index = $keys[$index];
        }

        unset($this->headers[$index]);
    }

    public function resetHeader($num, $name, $value)
    {
        $keys = array_keys($this->headers);
        $vals = array_values($this->headers);

        $keys[$num] = $name;
        $vals[$num] = $value;

        $this->headers = array_combine($keys, $vals);
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
        return $this->_response;
    }

    public function returnHttpCode()
    {
        return $this->_httpCode;
    }

    public function returnResponseInfo()
    {
        $returnValue = '';
        if ($this->_responseInfo !== '' && $this->_responseHeaders !== '') {
            $returnValue = array(
                "Response Info: "=>$this->_responseInfo,
                "Response Headers: "=>$this->_responseHeaders,
            );
        }
        return $returnValue;
    }

    private function saveRequest($requestObj)
    {

        $this->requestStorage->saveRequest(
            $requestObj['address'],
            $requestObj['method'],
            date("Y/m/d h:i:s"),
            $requestObj['getValues'],
            $requestObj['postValues'],
            $requestObj['_response'],
            $requestObj['_responseInfo'],
            $requestObj['_httpCode'],
            $requestObj['_responseHeaders'],
            $requestObj['request_headers']
        );

        $this->getValues = Array();
        $this->postValues = Array();
        $this->_httpCode = '';
        $this->_responseHeaders = '';
        $this->_responseInfo = '';
        $this->headers = Array();
        
    }

    public function returnSavedRequests()
    {
        return $this->requestStorage->returnRequests();
    }

    public function setBasicAuth($un, $pw)
    {
        $this->setHeader("Authorization",$un.":".$pw);        
    }

    public function replayRequest($request)
    {
        if (is_numeric($request)) {

            $replayObj = $this->requestStorage->returnSingleRequest($request);
            $this->address = $replayObj['address'];
            $this->method = $replayObj['method'];
            $this->getValues = $replayObj['get_values'];
            $this->postValues = $replayObj['post_values'];
            $this->headers = $replayObj['request_headers'];

            return $this->makeRequest();
        }

    }

    public function returnRequestList()
    {
        return $this->requestStorage->returnRequestList();
    }

    public function returnRequestListWithTimes()
    {
        return $this->requestStorage->returnRequestsListwithTimes();
    }

    public function resetStoredResponses()
    {
        $this->requestStorage->reset();
    }

    public function makeRequest()
    {
        if ($this->address !== "") {
            $this->_response = "";
            $requestObj = Array();
            $requestObj['globalAccept'] = $this->globalAccept;
            $requestObj['globalUser'] = $this->globalUser;
            $requestObj['getValues'] = $this->getValues;
            $requestObj['postValues'] = $this->postValues;
            $requestObj['address'] = $this->address;
            $requestObj['method'] = $this->method;
            $requestObj['returnPostFieldsForRequest'] = $this->returnPostFieldsForRequest();

            if ($this->globalAccept !== "") {
                $this->setHeader("Accept",$this->globalAccept);
            }
            if ($this->globalUser !== "") {
                $this->setHeader("User",$this->globalUser);
            }

            $requestObj['request_headers'] = $this->headers;

            // EXECUTE
            $response = $this->_request->makeRequest($requestObj);

            $this->_responseInfo = $response['responseInfo'];
            $requestObj['_responseInfo'] = $response['responseInfo'];
            $this->_responseHeaders = $response['responseHeaders'];
            $requestObj['_responseHeaders'] = $response['responseHeaders'];
            $this->_httpCode = $response['httpCode'];
            $requestObj['_httpCode'] = $response['httpCode'];
            $this->_response = $response['result'];
            $requestObj['_response'] = $response['result'];

            if ($this->storeRequests === true) {
                $this->saveRequest($requestObj);
            }
        }
    }

    public function returnPostFieldsForRequest()
    {
        if (is_array($this->postValues)) {
            return http_build_query($this->postValues);
        }
    }

    public function lookupHttpCode($lookUpCode = "")
    {
        $code = "";
        if ($lookUpCode) {
            $code = $lookUpCode;
        } else if ($this->_httpCode > 0) {
            $code = $this->_httpCode;
        }

        return $this->_httpCodes->returnCode($code);
    }
}


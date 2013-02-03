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
 * This object handling a list of URLs to batch process
 * and saves them to a returnable object
 * 
 */

namespace Kite\CurlBack;

use Kite\CurlBack\Curl;

class BatchHandler
{

    public $error = Array(); 
    public $requestHandler = ""; 
    public $masterRequestList = Array();

    private $_requiredKeys = Array(
        "address",
        "method"
    );

    public function __construct($requests)
    {
        $this->processBatchObj($requests);
        $this->requestHandler = new Curl("", true);
    }

    public function processBatchObj($requests)
    {
        if (is_array($requests) === true) {
            for($r=0; $r < count($requests); $r++) {
                $this->addRequest($requests, $r);
            }
        }
    }

    public function addRequest($request, $position = 0)
    {
        $this->verifyRequest($request,$position);
        if (isset($this->error[0]) === false) {
            $this->masterRequestList[] = $request[0];
        } else {
            return $this->error;
        }
    }

    public function clearRequests()
    {
        $this->masterRequestList = Array();
    }

    public function processRequests($current = 0)
    {

        $currentObject = $this->masterRequestList[$current];

        $this->requestHandler->setAddress($currentObject['address']);
        $this->requestHandler->customMethod($currentObject['method']);
        if (isset($currentObject['getValues'])) {
            $this->requestHandler->setGetValue($currentObject['getValues']);
        }
        if (isset($currentObject['postValues'])) {
            $this->requestHandler->setPostValue($currentObject['postValues']);
        }
        if (isset($currentObject['headers'])) {
            $this->requestHandler->setHeader($currentObject['headers']);
        }
        if (isset($currentObject['accept'])) {
            $this->requestHandler->setGlobalAccept($currentObject['headers']);
        }
        if (isset($currentObject['user'])) {
            $this->requestHandler->setGlobalUser($currentObject['user']);
        }
        if (isset($currentObject['un']) && isset($currentObject['pw'])) {
            $this->requestHandler->setBasicAuth(
                $currentObject['un'], $currentObject['pw']
            );
        }

        $this->requestHandler->makeRequest();

        $nextRequest = $current+1;

        if ($nextRequest < count($this->masterRequestList)) {
            $this->processRequests($nextRequest);
        }

    }

    public function returnResponses()
    {
        return $this->requestHandler->returnSavedRequests();
    }

    private function verifyRequest($request, $position)
    {
        if (is_array($request) !== false) {

            foreach ($this->_requiredKeys as $key) {
                if (array_key_exists($key, $request[0]) === false) {
                    $this->error[$position][] = "Your request is missing a required field - " . $key;
                }
            }

        }
    }

}

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
 * This response object holds the data of individual requests
 * along with methods for manipulating this data/storage
 * 
 */

namespace Kite\CurlBack;

class CurlResponse
{

    public $requests = array();

    public function saveRequest(
        $address,
        $method,
        $request_time,
        $get_values,
        $post_values,
        $response,
        $response_info,
        $http_code,
        $response_headers,
        $request_headers
    ){
        $next_request = count($this->requests);
        $this->requests[$next_request] = array(
            "address" => $address,
            "method" => $method,
            "request_time" => $request_time,
            "get_values" => $get_values,
            "post_values" => $post_values,
            "response" => $response,
            "response_info" => $response_info,
            "http_code" => $http_code,
            "response_headers" => $response_headers,
            "request_headers" => $request_headers
        );
         
    }

    public function returnRequests()
    {
        return $this->requests;
    }

    public function returnRequestList()
    {
        $returnArray = Array();

        foreach ($this->requests as $response) {
            $returnArray[] = $response['address']; 
        }

        return $returnArray;
    }

    public function returnRequestsListWithTimes()
    {
        $returnArray = Array();

        foreach ($this->requests as $response) {
            $returnArray[] = $response['request_time'] 
                             . " " .$response['address']; 
        }

        return $returnArray;
    }

    public function reset()
    {
        $this->requests = Array();
    }

    public function returnSingleRequest($request)
    {
        if (is_numeric($request)) {
            $this->requests[$request];
        }
    }

}


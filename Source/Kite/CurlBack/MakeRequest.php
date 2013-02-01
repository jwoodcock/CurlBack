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
 * This object handles making the request and passing back
 * any returned data
 * 
 */

namespace Kite\CurlBack;

class MakeRequest
{
    public function makeRequest($requestObj)
    {
        $address = $requestObj['address'];
        $globalAccept = $requestObj['globalAccept'];
        $getValues = $requestObj['getValues'];
        $postValues = $requestObj['postValues'];

        $response = Array();

        // Initiate CURL object
        $ch = curl_init();

        // Define CURL options
        $urlVariables = "";
        if (is_array($requestObj['getValues']) && count($requestObj['getValues']) > 0) {
            $urlVariables = "?" . http_build_query($requestObj['getValues']);
        }

        $curlUrl = $requestObj['address'] . $urlVariables;
        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        if ($requestObj['method'] === 'POST') {
            $fields = $requestObj['returnPostFieldsForRequest'];
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
            curl_setopt($ch, CURLOPT_POST, 1);
        } else if ($requestObj['method'] !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestObj['method']); 
            if ($requestObj['method'] === 'PUT') {
                $fields = $requestObj['returnPostFieldsForRequest'];
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
            }
        }
 
        if (count($requestObj['request_headers']) > 0) {
            $headers = array();
            foreach ($requestObj['request_headers'] as $key => $value) {
                $headers[] = $key . ": " . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // EXECUTE
        $result = curl_exec($ch);
        $response['responseInfo'] = curl_getinfo($ch);
        $response['responseHeaders'] = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        // CHECK FOR ERRORS
        if (curl_errno($ch)) {
            $response['result'] = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
        }

        $response['httpCode'] = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // CLOSE CONNECTION
        curl_close($ch);

        $response['result'] = $result;

        return $response;
    }
}


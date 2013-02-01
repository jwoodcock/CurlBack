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
 * This object handles http code look ups
 * 
 */

namespace Kite\CurlBack;

use Kite\CurlBack\CurlResponse;

class HttpCodes 
{

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

    public function returnCode($lookUpCode = '')
    {
        if ($lookUpCode && $lookUpCode !== 0) {
            return self::$httpCodes[$lookUpCode];
        } else {
            return 'no value provided';
        }
    }
}


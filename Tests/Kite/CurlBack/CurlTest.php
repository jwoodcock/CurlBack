<?php
namespace Kite\CurlBack;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    protected $curl;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->curl = new Curl();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Kite\CurlBack\Curl::__construct
     */
    public function testConstructNoParams()
    {
        $curl = new Curl();

        $this->assertInstanceOf('Kite\CurlBack\Curl', $curl);
        $this->assertEmpty($curl->address);
        $this->assertFalse($curl->storeRequests);
        $this->assertEmpty($curl->getValues);
    }

    /**
     * @covers Kite\CurlBack\Curl::__construct
     */
    public function testConstructParams()
    {
        $curl1 = new Curl('http://httpbin.org/');

        $this->assertInstanceOf('Kite\CurlBack\Curl', $curl1);
        $this->assertEquals('http://httpbin.org/', $curl1->address);
        $this->assertFalse($curl1->storeRequests);


        $curl2 = new Curl(null, true);

        $this->assertInstanceOf('Kite\CurlBack\Curl', $curl2);
        $this->assertEmpty($curl2->address);
        $this->assertTrue($curl2->storeRequests);


        $curl3 = new Curl('http://httpbin.org/', true);

        $this->assertInstanceOf('Kite\CurlBack\Curl', $curl3);
        $this->assertEquals('http://httpbin.org/', $curl3->address);
        $this->assertTrue($curl3->storeRequests);
    }

    /**
     * @covers Kite\CurlBack\Curl::setAddress
     */
    public function testSetAddress()
    {
        $this->assertEmpty($this->curl->setAddress('http://httpbin.org/'));
        $this->assertEquals('http://httpbin.org/', $this->curl->address);
    }

    /**
     * @covers Kite\CurlBack\Curl::echoAddress
     */
    public function testEchoAddress()
    {
        $this->expectOutputString('http://httpbin.org/');

        $this->curl->setAddress('http://httpbin.org/');
        $this->curl->echoAddress();
    }

    /**
     * @covers Kite\CurlBack\Curl::setGetValue
     */
    public function testSetGetValue()
    {
        $this->assertEmpty($this->curl->setGetValue("varible","value1"));
        $this->assertEquals(array("varible"=>"value1"), $this->curl->getValues);
    }

    /**
     * @covers Kite\CurlBack\Curl::setGetValue
     */
    public function testSetGetValueWithArray()
    {
        $getValues = array(
            'foo' => 'bar',
            'baz' => 1,
        );

        $this->assertEmpty($this->curl->setGetValue($getValues));
        $this->assertEquals($getValues, $this->curl->getValues);
    }

    /**
     * @covers Kite\CurlBack\Curl::setPostValue
     */
    public function testSetPostValue()
    {
        $this->assertEmpty($this->curl->setPostValue("varible","value1"));
        $this->assertEquals(
            array("varible"=>"value1"), 
            $this->curl->postValues
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::setPostValue
     */
    public function testSetPostValueWithArray()
    {
        $postValues = array(
            'foo' => 'bar',
            'baz' => 1,
        );

        $this->assertEmpty($this->curl->setPostValue($postValues));
        $this->assertEquals($postValues, $this->curl->postValues);
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToPost
     */
    public function testChangeToPost()
    {
        $this->assertEquals("GET",$this->curl->method);
        $this->curl->changeToPost();
        $this->assertEquals("POST",$this->curl->method);
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToPUT
     */
    public function testChangeToPUT()
    {
        $this->assertEquals("GET",$this->curl->method);
        $this->curl->changeToPut();
        $this->assertEquals("PUT",$this->curl->method);
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToDelete
     */
    public function testChangeToDelete()
    {
        $this->assertEquals("GET",$this->curl->method);
        $this->curl->changeToDelete();
        $this->assertEquals("DELETE",$this->curl->method);
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToGet
     * @todo   Implement testChangeToGet().
     */
    public function testChangeToGet()
    {
        $this->assertEquals("GET",$this->curl->method);
        $this->curl->changeToPut();
        $this->assertEquals("PUT",$this->curl->method);
        $this->curl->changeToGet();
        $this->assertEquals("GET",$this->curl->method);
    }

    /**
     * @covers Kite\CurlBack\Curl::customMethod
     */
    public function testCustomMethod()
    {
        $this->assertEquals("GET",$this->curl->method);
        $this->curl->customMethod('JACQUES');
        $this->assertEquals("JACQUES",$this->curl->method);
    }

    /**
     * @covers Kite\CurlBack\Curl::setHeader
     */
    public function testSetHeader()
    {
        $this->assertEmpty(
            $this->curl->setHeader('Content-type','application/html')
        );
        $this->assertEquals(
            array('Content-type: application/html'),$this->curl->headers
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::removeHeader
     */
    public function testRemoveHeader()
    {
        $this->assertEmpty(
            $this->curl->setHeader('Content-type','application/html')
        );
        $this->assertEquals(
            array('Content-type: application/html'),
            $this->curl->headers
        );
        $this->curl->removeHeader(0);
        $this->assertEmpty($this->curl->headers);
    }

    /**
     * @covers Kite\CurlBack\Curl::resetHeader
     */
    public function testResetHeader()
    {
        $this->assertEmpty(
            $this->curl->setHeader('Content-type','application/html')
        );
        $this->assertEquals(
            array('Content-type: application/html'),
            $this->curl->headers
        );
        $this->curl->resetHeader(0,'Content-type','application/json');
        $this->assertEquals(
            array('Content-type: application/json'),
            $this->curl->headers
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnHeaderCount
     */
    public function testReturnHeaderCount()
    {
        $this->assertEmpty(
            $this->curl->setHeader('Content-type','application/html')
        );
        $this->curl->setHeader('USER','Jacques');
        $this->assertEquals(2,$this->curl->returnHeaderCount());
    }

    /**
     * @covers Kite\CurlBack\Curl::setGlobalAccept
     */
    public function testSetGlobalAccept()
    {
        $this->assertEquals('application/json',$this->curl->globalAccept);
        $this->curl->setGlobalAccept('application/html');
        $this->assertEquals('application/html',$this->curl->globalAccept);
    }

    /**
     * @covers Kite\CurlBack\Curl::setGlobalUser
     */
    public function testSetGlobalUser()
    {
        $this->assertEmpty($this->curl->globalUser);
        $this->curl->setGlobalUser('UserJacques');
        $this->assertEquals('UserJacques',$this->curl->globalUser);
    }

    /**
     * @covers Kite\CurlBack\Curl::returnResponse
     */
    public function testReturnResponse()
    {
        $this->assertEmpty($this->curl->returnResponse());
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertNotEmpty($this->curl->returnResponse());
    }

    /**
     * @covers Kite\CurlBack\Curl::returnHttpCode
     */
    public function testReturnHttpCode()
    {
        $this->assertEmpty($this->curl->returnHttpCode());
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertEquals('200',$this->curl->returnHttpCode());
    }

    /**
     * @covers Kite\CurlBack\Curl::returnResponseInfo
     */
    public function testReturnResponseInfo()
    {
        $this->assertEmpty($this->curl->returnResponseInfo());
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertNotEmpty($this->curl->returnResponseInfo());
    }

    /**
     * @covers Kite\CurlBack\Curl::returnSavedRequests
     */
    public function testReturnSavedRequests()
    {
        $this->assertEmpty($this->curl->pastResponses);
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertNotEmpty($this->curl->pastResponses);
        $this->assertInternalType('array', $this->curl->returnSavedRequests());
    }

    /**
     * @covers Kite\CurlBack\Curl::setBasicAuth
     */
    public function testSetBasicAuth()
    {
        $this->assertEmpty($this->curl->setBasicAuth('username','password'));
        $this->assertEquals(
            array('Authorization: username:password' ),
            $this->curl->headers
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::replayRequest
     * @covers Kite\CurlBack\Curl::saveRequest
     */
    public function testReplayRequest()
    {
        $this->assertEmpty($this->curl->pastResponses);
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->curl->replayRequest(0);
        $this->assertEquals(2,count($this->curl->pastResponses));
    }

    /**
     * @covers Kite\CurlBack\Curl::replayRequest
     * @covers Kite\CurlBack\Curl::saveRequest
     */
    public function testReplayRequestWithNonNumeric()
    {
        $this->assertEmpty($this->curl->pastResponses);
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertEmpty($this->curl->replayRequest('foo'));
        $this->assertEquals(1, count($this->curl->pastResponses));
    }

    /**
     * @covers Kite\CurlBack\Curl::returnRequestList
     */
    public function testReturnRequestList()
    {
        $this->assertEmpty($this->curl->returnRequestList());
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->curl->setAddress('http://www.yahoo.com');
        $this->curl->makeRequest();
        $this->assertEquals(
            array("http://www.google.com","http://www.yahoo.com"),
            $this->curl->returnRequestList()
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnRequestListWithTimes
     */
    public function testReturnRequestListWithTimes()
    {
        $this->assertEmpty($this->curl->returnRequestListWithTimes());
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $time1 = date("Y/m/d h:i:s");
        $this->curl->setAddress('http://www.yahoo.com');
        $this->curl->makeRequest();
        $time2 = date("Y/m/d h:i:s");
        $this->assertEquals(
            array(
                $time1 . " http://www.google.com",
                $time2 . " http://www.yahoo.com"
            ),
            $this->curl->returnRequestListWithTimes()
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::resetStoredResponses
     */
    public function testResetStoredResponses()
    {
        $this->assertEmpty($this->curl->resetStoredResponses());
        $this->curl->storeRequests = true;
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->curl->setAddress('http://www.yahoo.com');
        $this->curl->makeRequest();
        $this->curl->resetStoredResponses();
        $this->assertEmpty($this->curl->pastResponses);
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestGet()
    {
        $this->assertEmpty($this->curl->makeRequest());
        $this->curl->setAddress('http://www.google.com');
        $response = $this->curl->makeRequest();
        $this->assertNotEmpty($this->curl->headers);
        $this->assertEquals("GET",$this->curl->method);
        $this->assertNotEmpty($this->curl->returnResponseInfo());
        $this->assertNotEmpty($this->curl->returnResponse());
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestPost()
    {
        $postValues = array(
            'foo' => 'bar',
            'baz' => 1,
            'qux' => 'Lorem ipsum dolar sit amet',
        );

        $this->curl->setAddress('http://httpbin.org/post');
        $this->curl->changeToPost();
        $this->curl->setPostValue($postValues);
        $this->curl->makeRequest();

        $this->assertNotEmpty($this->curl->headers);
        $this->assertEquals('POST', $this->curl->method);
        $this->assertNotEmpty($this->curl->returnResponseInfo());
        $this->assertNotEmpty($this->curl->returnResponse());

        $parsedResponse = json_decode($this->curl->returnResponse(), true);
        $this->assertEquals($postValues, $parsedResponse['form']);
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestDelete()
    {
        $this->curl->setAddress('http://httpbin.org/delete');
        $this->curl->changeToDelete();
        $this->curl->makeRequest();

        $this->assertNotEmpty($this->curl->headers);
        $this->assertEquals('DELETE', $this->curl->method);
        $this->assertNotEmpty($this->curl->returnResponseInfo());
        $this->assertNotEmpty($this->curl->returnResponse());
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestPut()
    {
        $postValues = array(
            'foo' => 'bar',
            'baz' => 1,
            'qux' => 'Lorem ipsum dolar sit amet',
        );

        $this->curl->setAddress('http://httpbin.org/put');
        $this->curl->changeToPut();
        $this->curl->setPostValue($postValues);
        $this->curl->makeRequest();

        $this->assertNotEmpty($this->curl->headers);
        $this->assertEquals('PUT', $this->curl->method);
        $this->assertNotEmpty($this->curl->returnResponseInfo());
        $this->assertNotEmpty($this->curl->returnResponse());

        $parsedResponse = json_decode($this->curl->returnResponse(), true);
        $this->assertEquals($postValues, $parsedResponse['form']);
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestWithGlobalUser()
    {
        $this->curl->setGlobalUser('TestUser');
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();

        $this->assertNotEmpty($this->curl->headers);
        $this->assertContains('User: TestUser', $this->curl->headers);
        $this->assertEquals('GET', $this->curl->method);
        $this->assertNotEmpty($this->curl->returnResponseInfo());
        $this->assertNotEmpty($this->curl->returnResponse());
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     */
    public function testMakeRequestWithError()
    {
        $this->curl->setAddress('http://404.php.net/');
        $this->curl->makeRequest();

        $this->assertEquals("ERROR -> 6: Couldn't resolve host '404.php.net'", $this->curl->returnResponse());
    }

    /**
     * @covers Kite\CurlBack\Curl::returnPostFieldsForRequest
     */
    public function testReturnPostFieldsForRequest()
    {
        $postValues = array(
            'foo' => 'bar',
            'baz' => 1,
            'qux' => 'Lorem ipsum dolar sit amet',
        );
        $postString = http_build_query($postValues);

        $this->curl->setPostValue($postValues);
        $this->assertEquals($postString, $this->curl->returnPostFieldsForRequest());
    }

    /**
     * @covers Kite\CurlBack\Curl::lookupHttpCode
     */
    public function testLookupHttpCode()
    {
        $this->assertEquals('no value provided', $this->curl->lookupHttpCode());
        $this->curl->setAddress('http://www.google.com');
        $this->curl->makeRequest();
        $this->assertEquals("200 OK", $this->curl->lookupHttpCode());
        $this->assertEquals("204 No Content", $this->curl->lookupHttpCode(204));
    }
}

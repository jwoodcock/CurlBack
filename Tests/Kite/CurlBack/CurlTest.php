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
     * @covers Kite\CurlBack\Curl::setPostValue
     */
    public function testSetPostValue()
    {
        $this->assertEmpty($this->curl->setPostValue("varible","value1"));
        $this->assertEquals(array("varible"=>"value1"), $this->curl->postValues);
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
        $this->assertEmpty($this->curl->setHeader('Content-type','application/html'));
        $this->assertEquals(array('Content-type: application/html'),$this->curl->headers);
    }

    /**
     * @covers Kite\CurlBack\Curl::removeHeader
     */
    public function testRemoveHeader()
    {
        $this->assertEmpty($this->curl->setHeader('Content-type','application/html'));
        $this->assertEquals(array('Content-type: application/html'),$this->curl->headers);
        $this->curl->removeHeader(0);
        $this->assertEmpty($this->curl->headers);
    }

    /**
     * @covers Kite\CurlBack\Curl::resetHeader
     */
    public function testResetHeader()
    {
        $this->assertEmpty($this->curl->setHeader('Content-type','application/html'));
        $this->assertEquals(array('Content-type: application/html'),$this->curl->headers);
        $this->curl->resetHeader(0,'Content-type','application/json');
        $this->assertEquals(array('Content-type: application/json'),$this->curl->headers);
    }

    /**
     * @covers Kite\CurlBack\Curl::returnHeaderCount
     */
    public function testReturnHeaderCount()
    {
        $this->assertEmpty($this->curl->setHeader('Content-type','application/html'));
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
    }

    /**
     * @covers Kite\CurlBack\Curl::setBasicAuth
     */
    public function testSetBasicAuth()
    {
        $this->assertEmpty($this->curl->setBasicAuth('username','password'));
        $this->assertEquals(array('Authorization: username:password' ),$this->curl->headers);
    }

    /**
     * @covers Kite\CurlBack\Curl::replayRequest
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
     * @covers Kite\CurlBack\Curl::returnRequestList
     * @todo   Implement testReturnRequestList().
     */
    public function testReturnRequestList()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnRequestListWithTimes
     * @todo   Implement testReturnRequestListWithTimes().
     */
    public function testReturnRequestListWithTimes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::resetStoredResponses
     * @todo   Implement testResetStoredResponses().
     */
    public function testResetStoredResponses()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::makeRequest
     * @todo   Implement testMakeRequest().
     */
    public function testMakeRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::lookupHttpCode
     * @todo   Implement testLookupHttpCode().
     */
    public function testLookupHttpCode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}

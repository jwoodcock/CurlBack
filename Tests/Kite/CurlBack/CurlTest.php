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
     * @todo   Implement testSetPostValue().
     */
    public function testSetPostValue()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToPost
     * @todo   Implement testChangeToPost().
     */
    public function testChangeToPost()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToPUT
     * @todo   Implement testChangeToPUT().
     */
    public function testChangeToPUT()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToDelete
     * @todo   Implement testChangeToDelete().
     */
    public function testChangeToDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::changeToGet
     * @todo   Implement testChangeToGet().
     */
    public function testChangeToGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::customMethod
     * @todo   Implement testCustomMethod().
     */
    public function testCustomMethod()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::setHeader
     * @todo   Implement testSetHeader().
     */
    public function testSetHeader()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::removeHeader
     * @todo   Implement testRemoveHeader().
     */
    public function testRemoveHeader()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::resetHeader
     * @todo   Implement testResetHeader().
     */
    public function testResetHeader()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnHeaderCount
     * @todo   Implement testReturnHeaderCount().
     */
    public function testReturnHeaderCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::setGlobalAccept
     * @todo   Implement testSetGlobalAccept().
     */
    public function testSetGlobalAccept()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::setGlobalUser
     * @todo   Implement testSetGlobalUser().
     */
    public function testSetGlobalUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnResponse
     * @todo   Implement testReturnResponse().
     */
    public function testReturnResponse()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnHttpCode
     * @todo   Implement testReturnHttpCode().
     */
    public function testReturnHttpCode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnResponseInfo
     * @todo   Implement testReturnResponseInfo().
     */
    public function testReturnResponseInfo()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::returnSavedRequests
     * @todo   Implement testReturnSavedRequests().
     */
    public function testReturnSavedRequests()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::setBasicAuth
     * @todo   Implement testSetBasicAuth().
     */
    public function testSetBasicAuth()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Kite\CurlBack\Curl::replayRequest
     * @todo   Implement testReplayRequest().
     */
    public function testReplayRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
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

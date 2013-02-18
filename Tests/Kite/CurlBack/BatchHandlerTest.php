<?php

namespace Kite\CurlBack;

use Kite\CurlBack\Curl;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-02 at 21:14:32.
 */
class BatchHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BatchHandler
     */
    protected $batchHandler;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET",
            )
        );
        $curl = new Curl("",true);
        $this->batchHandler = new BatchHandler($requests, $curl);
        $this->assertNotEmpty($this->batchHandler->requestHandler);
        $this->batchHandler->clearRequests();

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Kite\CurlBack\BatchHandler::processBatchObj
     */
    public function testProcessBatchObj()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET",
            ),
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET",
            )
        );
        $this->assertEmpty($this->batchHandler->masterRequestList);
        $this->batchHandler->processBatchObj($requests);
        $this->assertEmpty($this->batchHandler->error);
        $this->assertNotEmpty($this->batchHandler->masterRequestList);

    }

    /**
     * @covers Kite\CurlBack\BatchHandler::processBatchObj
     */
    public function testProcessBatchObjFail()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
            ),
        );
        $this->assertEmpty($this->batchHandler->error);
        $this->batchHandler->processBatchObj($requests);
        $this->assertNotEmpty($this->batchHandler->error);
        $this->assertEquals(
            'Your request is missing a required field - method',
            $this->batchHandler->error[0][0]
        );

    }

    /**
     * @covers Kite\CurlBack\BatchHandler::addRequest
     */
    public function testAddRequest()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET"
            ),
        );
        $this->assertEmpty($this->batchHandler->masterRequestList);
        $this->batchHandler->addRequest($requests);
        $this->assertNotEmpty($this->batchHandler->masterRequestList);
        $this->assertEquals(
            1,
            count($this->batchHandler->masterRequestList)
        );
    }

    /**
     * @covers Kite\CurlBack\BatchHandler::processRequests
     */
    public function testProcessRequests()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET",
                "getValues"=>array('one'=>'yes','two'=>'no'),
            ),
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"POST",
                "postValues"=>array('one'=>'yes','two'=>'no'),
            ),
        );
        $this->assertEmpty($this->batchHandler->returnResponses());
        $this->batchHandler->processBatchObj($requests);
        $this->assertEmpty($this->batchHandler->processRequests());
        $this->assertNotEmpty($this->batchHandler->returnResponses());

    }

    /**
     * @covers Kite\CurlBack\BatchHandler::returnResponses
     * @todo   Implement testReturnResponses().
     */
    public function testReturnResponses()
    {
        $requests = array(
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"GET",
                "getValues"=>array('one'=>'yes','two'=>'no'),
            ),
            array(
                "address"=>"http://httpbin.org/",
                "method"=>"POST",
                "postValues"=>array('one'=>'yes','two'=>'no'),
            ),
        );
        $this->assertEmpty($this->batchHandler->returnResponses());
        $this->batchHandler->processBatchObj($requests);
        $this->assertEmpty($this->batchHandler->processRequests());
        $response = $this->batchHandler->returnResponses();
        $this->assertEquals("200", $response[0]['http_code']);
        $this->assertEquals(2, count($response));

    }
}

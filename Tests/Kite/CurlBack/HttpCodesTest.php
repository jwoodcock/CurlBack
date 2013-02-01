<?php
namespace Kite\CurlBack;

class HttpCodesTest extends \PHPUnit_Framework_TestCase
{
    protected $code;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->code = new HttpCodes;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Kite\CurlBack\HttpCodes::returnCode
     */
    public function testReturnCode()
    {
        $this->assertEquals("499 Client Closed Request",$this->code->returnCode(499));
    }

    /**
     * @covers Kite\CurlBack\HttpCodes::returnCode
     */
    public function testReturnCodeNoCode()
    {
        $this->assertEquals("no value provided",$this->code->returnCode());
    }
}

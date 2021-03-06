<?php

require_once dirname(__FILE__) . '/../../../../Scorpio/Sco/Ticker.php';

/**
 * Test class for Sco_Ticker.
 * Generated by PHPUnit on 2012-07-01 at 09:43:52.
 */
class Sco_TickerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Sco_Ticker
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Sco_Ticker;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		
	}

	/**
	 * Generated from @assert (3) == 3.
	 *
	 * @covers Sco_Ticker::addTicker
	 */
	public function testAddTicker()
	{
		$this->assertEquals(
				3
				, $this->object->addTicker(3)
		);
	}

	/**
	 * Generated from @assert (3) == -3.
	 *
	 * @covers Sco_Ticker::subTicker
	 */
	public function testSubTicker()
	{
		$this->assertEquals(
				-3
				, $this->object->subTicker(3)
		);
	}

	/**
	 * @covers Sco_Ticker::__toString
	 * @todo Implement test__toString().
	 */
	public function test__toString()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::currentTicker
	 * @todo Implement testCurrentTicker().
	 */
	public function testCurrentTicker()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::setName
	 * @todo Implement testSetName().
	 */
	public function testSetName()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::getName
	 * @todo Implement testGetName().
	 */
	public function testGetName()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::setTicker
	 * @todo Implement testSetTicker().
	 */
	public function testSetTicker()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::getTicker
	 * @todo Implement testGetTicker().
	 */
	public function testGetTicker()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::resetTicker
	 * @todo Implement testResetTicker().
	 */
	public function testResetTicker()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Ticker::reflashTicker
	 * @todo Implement testReflashTicker().
	 */
	public function testReflashTicker()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

}

?>

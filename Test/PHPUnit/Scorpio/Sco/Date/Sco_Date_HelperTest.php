<?php

class Sco_Date_HelperTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers Sco_Date_Helper::secondsToTimeString
	 */
	public function testSecondsToTimeString()
	{
		$this->assertEquals('0 seconds', Sco_Date_Helper::secondsToTimeString(0));
		$this->assertEquals('1 second', Sco_Date_Helper::secondsToTimeString(1));
		$this->assertEquals('2 seconds', Sco_Date_Helper::secondsToTimeString(2));
		$this->assertEquals('01:00', Sco_Date_Helper::secondsToTimeString(60));
		$this->assertEquals('01:01', Sco_Date_Helper::secondsToTimeString(61));
		$this->assertEquals('02:00', Sco_Date_Helper::secondsToTimeString(120));
		$this->assertEquals('02:01', Sco_Date_Helper::secondsToTimeString(121));
		$this->assertEquals('01:00:00', Sco_Date_Helper::secondsToTimeString(3600));
		$this->assertEquals('01:00:01', Sco_Date_Helper::secondsToTimeString(3601));
	}

}

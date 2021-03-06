<?php

require_once dirname(__FILE__) . '/../../../../../Scorpio/Sco/Ticker/Iterator.php';

/**
 * Test class for Sco_Ticker_Iterator.
 * Generated by PHPUnit on 2012-07-01 at 13:26:07.
 */
class Sco_Ticker_IteratorTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Sco_Ticker_Iterator
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Sco_Ticker_Iterator;

		$this->object->a->addTicker(9);

		$this->object->b->subTicker(5);

		$this->object->f->addTicker(1);
		$this->object->c->addTicker(10);
		$this->object->d->addTicker(1);
		$this->object->append(2);
		$this->object->append(12);
		$this->object->append(20);
		$this->object->append(1);
		$this->object->append(1);
		$this->object->e->addTicker(1);
		$this->object->append(1);
		$this->object->append(2);
		$this->object->append(12);
		$this->object->append(20);
		$this->object->append(1);
		$this->object->append(1);
		$this->object->append(2);
		$this->object->append(12);
		$this->object->append(20);
		$this->object->append(1);
		$this->object->append(1);
		$this->object->append(2);
		$this->object->append(12);
		$this->object->append(20);
		$this->object->append(1);
		$this->object->append(1);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{

	}

	/**
	 * @covers Sco_Ticker_Iterator::offsetSet
	 * @todo Implement testOffsetSet().
	 */
	public function testOffsetSet()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Sco_Ticker_Iterator::offsetGet
	 * @todo Implement testOffsetGet().
	 */
	public function testOffsetGet()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Sco_Ticker_Iterator::exchangeArray
	 * @todo Implement testExchangeArray().
	 */
	public function testExchangeArray()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Sco_Ticker_Iterator::sort
	 * @todo Implement testSort().
	 */
	public function testSort()
	{
		$this->object->sort();

		$this->assertSame(array(
			'b' => -5,
			'f' => 1,
			'd' => 1,
			3 => 1,
			4 => 1,
			'e' => 1,
			5 => 1,
			9 => 1,
			10 => 1,
			14 => 1,
			15 => 1,
			19 => 1,
			20 => 1,
			0 => 2,
			6 => 2,
			11 => 2,
			16 => 2,
			'a' => 9,
			'c' => 10,
			1 => 12,
			7 => 12,
			12 => 12,
			17 => 12,
			2 => 20,
			8 => 20,
			13 => 20,
			18 => 20,
			), $this->object->toArrayValues());
	}

	/**
	 * @covers Sco_Ticker_Iterator::usort
	 * @todo Implement testUsort().
	 */
	public function testUsort()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Sco_Ticker_Iterator::toArrayValues
	 * @todo Implement testToArrayValues().
	 */
	public function testToArrayValues()
	{
		$this->assertSame(array(
			'a' => 9,
			'b' => -5,
			'f' => 1,
			'c' => 10,
			'd' => 1,
			0 => 2,
			1 => 12,
			2 => 20,
			3 => 1,
			4 => 1,
			'e' => 1,
			5 => 1,
			6 => 2,
			7 => 12,
			8 => 20,
			9 => 1,
			10 => 1,
			11 => 2,
			12 => 12,
			13 => 20,
			14 => 1,
			15 => 1,
			16 => 2,
			17 => 12,
			18 => 20,
			19 => 1,
			20 => 1,
			), $this->object->toArrayValues());
	}

	/**
	 * @covers Sco_Ticker_Iterator::apply_exec
	 * @todo Implement testApply_exec().
	 */
	public function testApply_exec()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Sco_Ticker_Iterator::apply
	 * @todo Implement testApply().
	 */
	public function testApply()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

}


?>

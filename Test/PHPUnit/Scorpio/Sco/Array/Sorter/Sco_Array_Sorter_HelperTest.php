<?php

require_once dirname(__FILE__) . '/../../../../../../Scorpio/Sco/Array/Sorter/Helper.php';

/**
 * Test class for Sco_Array_Sorter_Helper.
 * Generated by PHPUnit on 2012-06-30 at 03:53:15.
 */
class Sco_Array_Sorter_HelperTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Sco_Array_Sorter_Helper
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Sco_Array_Sorter_Helper;

		include_once(TEST_PATH_FIXTURES.'stable.php');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{

	}

	/**
	 * @covers Sco_Array_Sorter_Helper::merge_sort
	 * @todo Implement testMerge_sort().
	 */
	public function testMerge_sort()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Sco_Array_Sorter_Helper::merge_sort_assoc
	 * @todo Implement testMerge_sort_assoc().
	 */
	public function testMerge_sort_assoc()
	{
		Sco_Array_Sorter_HelperTest_Builder::buildTest($this, array('Sco_Array_Sorter_Helper', 'merge_sort_assoc'), true);
	}

	/**
	 * @covers Sco_Array_Sorter_Helper::stable_asort
	 * @todo Implement testStable_asort().
	 */
	public function testStable_asort()
	{
		Sco_Array_Sorter_HelperTest_Builder::buildTest($this, array('Sco_Array_Sorter_Helper', 'stable_asort'));
	}

	/**
	 * @covers Sco_Array_Sorter_Helper::stable_asort2
	 * @todo Implement testStable_asort2().
	 */
	public function testStable_asort2()
	{
		Sco_Array_Sorter_HelperTest_Builder::buildTest($this, array('Sco_Array_Sorter_Helper', 'stable_asort2'));
	}

}

?>

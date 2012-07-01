<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

if (!class_exists('Sco_Array_Sorter_HelperTest_Builder', false))
{
	class Sco_Array_Sorter_HelperTest_Builder
	{

		public static function buildTest(&$pnpunit, $callback, $usort = false)
		{
			$stable_array = self::provider();

			foreach ($stable_array['source'] as $idx => $data)
			{
				if (is_callable($stable_array['cmp_function'][$idx]))
				{
					if (!$usort) continue;

					$ret = call_user_func($callback, &$stable_array['source'][$idx], $stable_array['cmp_function'][$idx]);
				}
				else
				{
					$ret = call_user_func($callback, &$stable_array['source'][$idx]);
				}

				//$pnpunit->assertEquals($stable_array['expected'][$idx], $ret);
				$pnpunit->assertSame($stable_array['expected'][$idx], $ret);
			}
		}

		public static function provider()
		{
			$stable_array = array();

			$stable_array['source'][0] = array(

				0 => array('name' => 'Albert', 'last' => 'Einstein'),
				1 => array('name' => 'Lieserl', 'last' => 'Einstein'),
				2 => array('name' => 'Alan', 'last' => 'Turing'),
				3 => array('name' => 'Mileva', 'last' => 'Einstein'),
				4 => array('name' => 'Hans Albert', 'last' => 'Einstein'),

				);

			$stable_array['expected'][0] = array(

				2 => array(
					'name' => 'Alan',
					'last' => 'Turing',
					),
				0 => array(
					'name' => 'Albert',
					'last' => 'Einstein',
					),
				4 => array(
					'name' => 'Hans Albert',
					'last' => 'Einstein',
					),
				1 => array(
					'name' => 'Lieserl',
					'last' => 'Einstein',
					),
				3 => array(
					'name' => 'Mileva',
					'last' => 'Einstein',
					),

				);

			$stable_array['source'][1] = $stable_array['source'][0];

			$stable_array['expected'][1] = array(

				0 => array(
					'name' => 'Albert',
					'last' => 'Einstein',
					),
				1 => array(
					'name' => 'Lieserl',
					'last' => 'Einstein',
					),
				3 => array(
					'name' => 'Mileva',
					'last' => 'Einstein',
					),
				4 => array(
					'name' => 'Hans Albert',
					'last' => 'Einstein',
					),
				2 => array(
					'name' => 'Alan',
					'last' => 'Turing',
					),

				);

			$stable_array['cmp_function'][1] = function ($a, $b)
			{
				return strcmp($a['last'], $b['last']);
			}
			;

			$stable_array['source'][2] = array(
				"item1" => -1,
				"item2" => -1,
				"item3" => -1,
				"item4" => 0,
				"item5" => 2,
				"item6" => 2,
				"item7" => 1);

			$stable_array['expected'][2] = array(

				"item1" => -1,
				"item2" => -1,
				"item3" => -1,
				"item4" => 0,
				"item7" => 1,
				"item5" => 2,
				"item6" => 2,

				);

			return $stable_array;
		}
	}
}

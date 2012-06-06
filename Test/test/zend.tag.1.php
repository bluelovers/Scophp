<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

// Create the item list
$list = new Zend_Tag_ItemList();

// Assign tags to it
$list[] = new Zend_Tag_Item(array('title' => 'Code', 'weight' => 50));
$list[] = new Zend_Tag_Item(array('title' => 'Zend Framework', 'weight' => 1));
$list[] = new Zend_Tag_Item(array('title' => 'PHP', 'weight' => 5));

// Spread absolute values on the items
$list->spreadWeightValues(array(
	1,
	2,
	3,
	4,
	5,
	6,
	7,
	8,
	9,
	10));

// Output the items with their absolute values
foreach ($list as $item)
{
	printf("%s: %d\n", $item->getTitle(), $item->getParam('weightValue'));
}

var_dump(0x0, 0x1, 0x2, 0x3);
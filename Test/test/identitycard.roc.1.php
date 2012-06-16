<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$id = 'A127182075';
$valid = Sco_IdentityCard_ROC::valid($id);

var_dump($id, $valid);

for ($i = 0; $i < 100; $i++)
{
	$id = Sco_IdentityCard_ROC::generate();
	$valid = Sco_IdentityCard_ROC::valid($id);

	if (!$valid)
	{
		var_dump('-------------------------', $id, $valid);
		break;
	}
	else
	{
		var_dump($id);
	}

}

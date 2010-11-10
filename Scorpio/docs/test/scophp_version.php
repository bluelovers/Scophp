<?php

/**
 *
 * $HeadURL: https://bluelovers.net $
 * $Revision: $
 * $Author: $
 * $Date: $
 * $Id: $
 *
 * @author bluelovers
 * @copyright 2010
 */

include_once '../../Bootstrap.php';

foreach(array(
	'6.0.0', '5.3.3', '5.3.0', '5.2.0', '5.0.0', '5.x', '5.0', '5', '6.x', 'x'
) as $_v) {
	echolf('=== '.$_v);
	foreach (array(
		null, true, false, '', '>', '<', '>=', '<=', '=', '!=', '==', '<>'
	) as $_o) {
		$ret = $_o !== null ? version_compare(PHP_VERSION, $_v, $_o) : version_compare(PHP_VERSION, $_v);
		$ret2 = scophp::version($_v, $_o);

		$s = PHP_VERSION.TAB.var_string($_o).TAB.$_v.TAB.'='.TAB.var_string($ret).TAB.var_string($ret2);

		echolf($ret !== $ret2 ? '<span style="color: red;">'.$s.'</span>' : $s);
	}
}

?>
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

include_once './Bootstrap.php';

$scophp = scophp::instance();

echolf(scophp::ini_get('disable_functions'));
echolf(scophp::ini_get('safe_mode'));
echolf(scophp::ini_get('memory_limit'));
echolf(scophp::ini_get('register_globals'));

echolf(scophp::ini_get());

print_r($scophp);
@echolf($scophp['_INI']);
@echolf('-------------------');
@echolf($scophp);
@echolf('-------------------');
foreach($scophp as $_k => $_v) {
	echolf($_k);
	echolf($_v);
}

?>
<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

printnl(date(DATE_RFC822, strtotime("1 day")));

$m = Sco_Date_Helper::microtime(true);

printnl(date(Sco_Date_Helper::date_format_fix('D, d M Y H:i:s uuu T', $m), $m));

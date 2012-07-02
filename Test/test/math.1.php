<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$array = array();

$array[] = array(0, 0);
$array[] = array(0, 1);
$array[] = array(1, 1);
$array[] = array(1, 0);

$array[] = array(0, -1);
$array[] = array(-1, -1);
$array[] = array(-1, 0);
$array[] = array(-1, -1);

foreach ($array as $d)
{
	printnl(sprintf('(%+d, %+d) == %d', $d[0], $d[1], Sco_Math_Func_Distance::azimuth_compass($d[0], $d[1])));
	printnl(sprintf('(%+d, %+d) == %d', $d[0], $d[1], Sco_Math_Func_Distance::azimuth_compass2($d[0], $d[1])));
}


function returnRotatedPoint($x,$y,$cx,$cy,$a)
    {
    # http://mathforum.org/library/drmath/view/63184.html
    global $_rotation;     # -1 = counter, 1 = clockwise
    global $_precision;    # two decimal places


            // radius using distance formula
            $r = sqrt(pow(($x-$cx),2)+pow(($y-$cy),2));
            // initial angle in relation to center
            $iA = $_rotation * rad2deg(atan2(($y-$cy),($x-$cx)));

            $nx = number_format($r * cos(deg2rad($_rotation * $a + $iA)),$_precision);
            $ny = number_format($r * sin(deg2rad($_rotation * $a + $iA)),$_precision);

    return array("x"=>$cx+$nx,"y"=>$cy+$ny);
    }
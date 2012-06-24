<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$qr = Sco_Chart_QRCode::newInstance();
$qr_adapter = $qr->getAdapter();

$qr->setSize(150);
$qr->setOptions(array('margin' => 1));

$qr->do_url('http://chat.in-here.us')->make()->createURI();

$qr_adapter->createFile();

var_dump($qr_adapter);

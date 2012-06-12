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

printnl($qr->do_url('http://chat.in-here.us')->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_bookmark("WebcodingEasy.com", "http://webcodingeasy.com")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_text("Any UTF8 characters like Ä&#65533;Ä“Å«")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_smsto("12345678", "sms text")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_tel("12345678")->make()->createURI());
printnl($qr_adapter->getContent());
printnl($qr_adapter->createHtml());

printnl($qr->do_mailto("test@test.com", "Testing email subject", "Testing email text")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_geo("40.71872", "-73.98905", "100")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

printnl($qr->do_wifi("wifi_name", "WEP", "password")->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());

$param = array();
$param[] = array("name" => "name1", "value" => "value1");
$param[] = array("name" => "name2", "value" => "value2");

printnl($qr->do_iappli("http://www.nttdocomo.co.jp/test_appli.jam", "abcde", $param)->make()->createURI());
printnl($qr_adapter->getContent());
printnl(http_build_query(array('chl' => $qr_adapter->getContent())));
printnl($qr_adapter->createHtml());
$qr_adapter->createFile();

var_dump($qr, $qr_adapter);

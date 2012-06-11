<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Chart_QRCode_Adapter_Google extends Sco_Chart_QRCode_Adapter_Abstract
{

	public function createURI()
	{
		return sprintf('http://chart.apis.google.com/chart?chs=%1$dx%1$d&cht=qr&chld=%2$s|%4$d&chl=%3$s', $this->_options['size'], $this->_options['ez'], $this->_content, $this->_options['margin']);
	}

}

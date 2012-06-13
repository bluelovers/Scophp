<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Chart_QRCode_Adapter_Google extends Sco_Chart_QRCode_Adapter_Abstract
{

	const URI = 'http://chart.apis.google.com/chart';
	const URI_ARGV = 'chs=%1$dx%1$d&cht=qr&chld=%2$s|%4$d&chl=%3$s&choe=%5$s';

	public function createURI()
	{
		if ($this->_make() && isset($this->uri))
		{
			return (string )$this->uri;
		}

		return $this->uri = sprintf(self::URI . '?' . self::URI_ARGV, $this->_options['size'], $this->_options['ec'], urlencode($this->_content), $this->_options['margin'], $this->_options['charset']);
	}

	/**
	 * getting image
	 */
	public function createImage($type = null)
	{

		if ($this->_make() && isset($this->im) && isset($this->type))
		{
			return array($this->im, $this->type);
		}

		$this->type = ($type) ? (string )$type : (string )$this->_options['type'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::URI);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf(self::URI_ARGV, $this->_options['size'], $this->_options['ec'], urlencode($this->_content), $this->_options['margin'], $this->_options['charset']));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);

		$this->im = curl_exec($ch);
		curl_close($ch);

		return array($this->im, $this->type);
	}

	public function createFile($file = null, $type = null)
	{
		if ($this->_make() && isset($this->file))
		{
			return (string )$this->file;
		}

		list($this->im, $this->type) = $this->createImage($type);
		$this->file = $this->_file($file);

		file_put_contents($this->file, $this->im, LOCK_EX);

		return $this->file;
	}

	public function createHtml()
	{
		if ($this->_make() && isset($this->html))
		{
			return (string )$this->html;
		}

		return $this->html = sprintf('<img src="%s" border="0" />', $this->createURI());
	}

}

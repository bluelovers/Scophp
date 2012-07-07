<?php

/**
 * @author bluelovers
 * @copyright 2012
 *
 * @author http://www.talkphp.com/script-giveaway/1905-gravatar-wrapper-class.html
 */

class Sco_Api_Avatar_Gravatar implements Sco_Api_Avatar_Interface
{

	const SITE = 'http://www.gravatar.com/';
	const SITE_SECURE = 'https://secure.gravatar.com/';

	//const GRAVATAR_SITE_URL = 'avatar.php?gravatar_id=%ssize=%sdefault=%srating=%s&default=%s';
	const SITE_URL = 'avatar/%s?s=%s&d=%s&r=%s&f=%s';

	const DEFAULT_404 = 404;
	const DEFAULT_MM = 'mm';
	const DEFAULT_IDENTICON = 'identicon';
	const DEFAULT_MONSTERID = 'monsterid';
	const DEFAULT_WAVATAR = 'wavatar';
	const DEFAULT_RETRO = 'retro';

	const SIZE_80 = 80;

	const RATING_G = 'g';
	const RATING_PG = 'pg';
	const RATING_R = 'r';
	const RATING_X = 'x';

	const YES = 'y';
	const NO = 'n';

	protected $m_szEmail;

	protected $m_iSize = self::SIZE_80;
	protected $m_szImage = self::DEFAULT_MM;
	protected $m_szRating = self::RATING_G;

	protected $m_force_default = self::NO;
	protected $m_secure_requests = false;

	public function __construct($email, $s = null, $d = null, $r = null, $f = null)
	{
		$this->setEmail($email);

		$s !== null && $this->setSize($s);
		$d !== null && $this->setDefaultImage($d);
		$r !== null && $this->setRating($r);
		$f !== null && $this->setDefaultImageForce($f);
	}

	public function __toString()
	{
		return (string )$this->getAvatar();
	}

	public function getAvatar()
	{
		return sprintf(($this->m_secure_requests ? self::SITE_SECURE : self::SITE) . self::SITE_URL, $this->m_szEmail, $this->m_iSize, $this->m_szImage, $this->m_szRating, $this->m_force_default);
	}

	public function setSecureRequests($flag = true)
	{
		$this->m_secure_requests = $flag;
		return $this;
	}

	public function setDefaultImage($szImage)
	{
		$this->m_szImage = urlencode($szImage);
		return $this;
	}

	public function setDefaultImageForce($flag = true)
	{
		$this->m_force_default = $flag ? self::YES : self::NO;
		return $this;
	}

	public function setDefaultImageAs404()
	{
		$this->m_szEmail = self::DEFAULT_404;
		return $this;
	}

	public function setDefaultImageAsMm()
	{
		$this->m_szEmail = self::DEFAULT_MM;
		return $this;
	}

	public function setDefaultImageAsMonsterId()
	{
		$this->m_szEmail = self::DEFAULT_MONSTERID;
		return $this;
	}

	public function setDefaultImageAsIdentIcon()
	{
		$this->m_szEmail = self::DEFAULT_IDENTICON;
		return $this;
	}

	public function setDefaultImageAsWavatar()
	{
		$this->m_szEmail = self::DEFAULT_WAVATAR;
		return $this;
	}

	public function setDefaultImageAsRetro()
	{
		$this->m_szEmail = self::DEFAULT_RETRO;
		return $this;
	}

	public function setEmail($szEmail)
	{
		$this->m_szEmail = md5(strtolower(trim($szEmail)));
		return $this;
	}

	public function setSize($iSize)
	{
		$this->m_iSize = urlencode((int)$iSize ? (int)$iSize : self::SIZE_80);
		return $this;
	}

	public function setRating($r)
	{
		$this->m_szRating = urlencode($r);
		return $this;
	}

	public function setRatingAsG()
	{
		$this->m_szRating = self::RATING_G;
		return $this;
	}

	public function setRatingAsPG()
	{
		$this->m_szRating = self::RATING_PG;
		return $this;
	}

	public function setRatingAsR()
	{
		$this->m_szRating = self::RATING_R;
		return $this;
	}

	public function setRatingAsX()
	{
		$this->m_szRating = self::RATING_X;
		return $this;
	}
}

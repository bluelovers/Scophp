<?php

/**
 * @author bluelovers
 * @copyright 2012
 *
 * @author http://www.talkphp.com/script-giveaway/1905-gravatar-wrapper-class.html
 */

class TalkPHP_Gravatar
{
	private $m_szImage;
	private $m_szEmail;
	private $m_iSize;
	private $m_szRating;
	private $m_szDefaultImage;

	const GRAVATAR_SITE_URL = 'http://www.gravatar.com/avatar.php?gravatar_id=%ssize=%sdefault=%srating=%s&default=%s';

	public function __construct()
	{
		$this->m_iSize = 80;
		$this->m_szRating = 'R';
		$this->m_szImage = '';
		$this->m_szDefaultImage = '';
	}

	public function getAvatar()
	{
		return (string )sprintf(self::GRAVATAR_SITE_URL, $this->m_szEmail, $this->m_iSize, $this->m_szImage, $this->m_szRating, $this->m_szDefaultImage);
	}

	public function setImage($szImage)
	{
		$this->m_szImage = (string )urlencode($szImage);
		return $this;
	}

	public function setDefaultImageAsIdentIcon()
	{
		$this->m_szDefaultImage = 'identicon';
		return $this;
	}

	public function setDefaultImageAsMonsterId()
	{
		$this->m_szDefaultImage = 'monsterid';
		return $this;
	}

	public function setDefaultImageAsWavatar()
	{
		$this->m_szDefaultImage = 'wavatar';
		return $this;
	}

	public function setEmail($szEmail)
	{
		$this->m_szEmail = (string )md5($szEmail);
		return $this;
	}

	public function setSize($iSize)
	{
		$this->m_iSize = (int)$iSize;
		return $this;
	}

	public function setRatingAsG()
	{
		$this->m_szRating = 'G';
		return $this;
	}

	public function setRatingAsPG()
	{
		$this->m_szRating = 'PG';
		return $this;
	}

	public function setRatingAsR()
	{
		$this->m_szRating = 'R';
		return $this;
	}

	public function setRatingAsX()
	{
		$this->m_szRating = 'X';
		return $this;
	}
}

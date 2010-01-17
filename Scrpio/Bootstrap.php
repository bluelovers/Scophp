<?php

/**
 *
 * $HeadURL$
 * $Revision$
 * $Author$
 * $Date$
 * $Id$
 *
 * @author bluelovers
 * @copyright 2010
 */

if (!defined('SYSPATH')) {
	require dirname(__FILE__).'/libs/File.php';

	define('SYSPATH', Scrpio_File_Core::dirname(__FILE__, '..', 1));
}

require_once(SYSPATH . 'Scrpio/system/Base.php');
require_once(SYSPATH . 'Scrpio/libs/Constants.php');

Scrpio_SYS_Base_Core::Init();

Sco_Base::Setup();

Sco_Base::Start();

?>
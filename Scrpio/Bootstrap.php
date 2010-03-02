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

	define('SYSPATH', Scorpio_File_Core::dirname(__FILE__, '..', 1));
}

require_once(SYSPATH . 'Scorpio/system/Base.php');
require_once(SYSPATH . 'Scorpio/libs/Constants.php');

Scorpio_SYS_Base_Core::Init();

Sco_Base::Setup();

Sco_Base::Start();

?>
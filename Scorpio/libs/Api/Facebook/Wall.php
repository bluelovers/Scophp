<?

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

if (0) {
	// for IDE
	class Scorpio_Api_Facebook_Wall extends Scorpio_Api_Facebook_Wall_Core_ {
	}
}

class Scorpio_Api_Facebook_Wall_Core_ extends Scorpio_Api_Facebook_Class {
	public static $fields = array(
		/*
<div class="UIImageBlock_Content UIImageBlock_MED_Content fsm fwn fcg">
	<div class="uiAttachmentTitle"><strong><span><a rel="nofollow" onmousedown="UntrustedLink.bootstrap($(this), &quot;8445a&quot;, event);" href="{link}">{name}</a></span></strong> </div>
	{caption}
	<div class="mts uiAttachmentDesc">{description}</div>
</div>
		*/
		//The link attached to this post
		'link',
		//The name of the link
		'name',
		//The caption of the link (appears beneath the link name)
		'caption',
		//A description of the link (appears beneath the link caption)
		'description',

		//If available, a link to the picture included with this post
		'picture',

		//The message
		'message',

		//If available, the source link attached to this post (for example, a flash or video file)
		'source',
	);

	public function __construct(&$core, $is_me = false, $uid = 0) {
		parent::__construct(&$core);

		$this->_data['is_me'] = $is_me;
		$this->_data['uid'] = $is_me ? (string)$this->core->getUser() : (string)$uid;
	}

	function id() {
		return $this->_data['uid'];
	}

	function is_me() {
		return $this->_data['is_me'];
	}

	function post($params = array(), $type = null, $action = 'feed') {
		$who = $this->_data['is_me'] ? 'me' : (string)$this->_data['uid'];

		$params = array_filter($params);

		if ($type == 'message'
//			|| ($type == null && !empty($params['message']))
		) {
			$type == 'message';

//			unset($params['link'], $params['description'], $params['name'], $params['caption']);
//			unset($params['link'], $params['name']);
		}

		// fix bug: if message index > link index
		$_params = $this->_ksort_by_array($params, self::$fields, 1);
//		$_params = $params;

		return $this->core->api('/'.(string)$who.'/'.$action, 'POST', (array)$_params);
	}
}

?>
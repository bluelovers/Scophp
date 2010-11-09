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
	class Scorpio_Api_Facebook extends Scorpio_Api_Facebook_Core {
	}
}

Scorpio_Loader_Core::instance()
	->extend('Facebook', 'D:/xampp/svn/clone/facebook/php-sdk/src/facebook.php')
	->load('Scorpio_Loader');

class Scorpio_Api_Facebook_Core extends Facebook {
	protected $_opts = array();
	public static $req_perms = array(
		/* Publishing Permissions */

		//Enables your application to post content, comments, and likes to a user's stream and to the streams of the user's friends. With this permission, you can publish content to a user's feed at any time, without requiring offline_access. However, please note that Facebook recommends a user-initiated sharing model.
		'publish_stream',
		//Enables your application to create and modify events on the user's behalf
		'create_event',
		//Enables your application to RSVP to events on the user's behalf
		'rsvp_event',
		//Enables your application to send messages to the user and respond to messages from the user via text message
		'sms',
		//Enables your application to perform authorized requests on behalf of the user at any time. By default, most access tokens expire after a short time period to ensure applications only make requests on behalf of the user when the are actively using the application. This permission makes the access token returned by our OAuth endpoint long-lived.
		'offline_access',

		/* Data Permissions - User permission */

		//Provides access to the "About Me" section of the profile in the about property
		'user_about_me',
		//Provides access to the user's list of activities as the activities connection
		'user_activities',
		//Provides access to the birthday with year as the birthday_date property
		'user_birthday',
		//Provides access to education history as the education property
		'user_education_history',
		//Provides access to the list of events the user is attending as the events connection
		'user_events',
		//Provides access to the list of groups the user is a member of as the groups connection
		'user_groups',
		//Provides access to the user's hometown in the hometown property
		'user_hometown',
		//Provides access to the user's list of interests as the interests connection
		'user_interests',
		//Provides access to the list of all of the pages the user has liked as the likes connection
		'user_likes',
		//Provides access to the user's current location as the current_location property
		'user_location',
		//Provides access to the user's notes as the notes connection
		'user_notes',
		//Provides access to the user's online/offline presence
		'user_online_presence',
		//Provides access to the photos the user has been tagged in as the photos connection
		'user_photo_video_tags',
		//Provides access to the photos the user has uploaded
		'user_photos',
		//Provides access to the user's family and personal relationships and relationship status
		'user_relationships',
		//Provides access to the user's relationship preferences                                                                                                                                                                                                                                ,
		'user_relationship_details',
		//Provides access to the user's religious and political affiliations
		'user_religion_politics',
		//Provides access to the user's most recent status message
		'user_status',
		//Provides access to the videos the user has uploaded
		'user_videos',
		//Provides access to the user's web site URL
		'user_website',
		//Provides access to work history as the work property
		'user_work_history',
		//Provides access to the user's primary email address in the email property. Do not spam users. Your use of email must comply both with Facebook policies and with the CAN-SPAM Act.
		'email',
		//Provides read access to any friend lists the user created. NOTE: All user's friends are provided as part of basic data, this extended permission grants access to the lists of friends a user has created, and should only be requested if your application utilizes lists of friends.
		'read_friendlists',
		//Provides read access to the Insights data for pages, applications, and domains the user owns.
		'read_insights',
		//Provides the ability to read from a user's Facebook Inbox. You must request to be whitelisted before you can prompt for this permission.
		'read_mailbox',
		//Provides read access to the user's friend requests
		'read_requests',
		//Provides access to all the posts in the user's News Feed and enables your application to perform searches against the user's News Feed
		'read_stream',
		//Provides applications that integrate with Facebook Chat the ability to log in users.
		'xmpp_login',
		//Provides the ability to manage ads and call the Facebook Ads API on behalf of a user.
		'ads_management',
		//Provides read access to the authorized user's check-ins or a friend's check-ins that the user can see.
		'user_checkins',

		/* Data Permissions - Friends permission */

		'user_about_me'				=>	'friends_about_me',
		'user_activities'			=>	'friends_activities',
		'user_birthday'				=>	'friends_birthday',
		'user_education_history'	=>	'friends_education_history',
		'user_events'				=>	'friends_events',
		'user_groups'				=>	'friends_groups',
		'user_hometown'				=>	'friends_hometown',
		'user_interests'			=>	'friends_interests',
		'user_likes'				=>	'friends_likes',
		'user_location'				=>	'friends_location',
		'user_notes'				=>	'friends_notes',
		'user_online_presence'		=>	'friends_online_presence',
		'user_photo_video_tags'		=>	'friends_photo_video_tags',
		'user_photos'				=>	'friends_photos',
		'user_relationships'		=>	'friends_relationships',
		'user_relationship_details'	=>	'friends_relationship_details',
		'user_religion_politics'	=>	'friends_religion_politics',
		'user_status'				=>	'friends_status',
		'user_videos'				=>	'friends_videos',
		'user_website'				=>	'friends_website',
		'user_work_history'			=>	'friends_work_history',
		'email'						=>	null,
		'read_friendlists'			=>	null,
		'read_insights'				=>	null,
		'read_mailbox'				=>	null,
		'read_requests'				=>	null,
		'read_stream'				=>	null,
		'xmpp_login'				=>	null,
		'ads_management'			=>	null,
		'user_checkins'				=>	'friends_checkins',

		/* Page Permissions */

		//Enables your application to retrieve access_tokens for pages the user administrates. The access tokens can be queried using the "accounts" connection in the Graph API. This permission is only compatible with the Graph API.
		'manage_pages',

	);

	static $apilist = array(
		'/{:who:}',
		'/{:who:}/friends',
		'/{:who:}/home',
		'/{:who:}/feed',
		'/{:who:}/movies',
		'/{:who:}/books',
		'/{:who:}/notes',
		'/{:who:}/photos',
		'/{:who:}/albums',
		'/{:who:}/videos',
		'/{:who:}/videos/uploaded',
		'/{:who:}/events',
		'/{:who:}/groups',
		'/{:who:}/checkins',

		'/{:who:}/accounts',
		'/{:who:}/family',
		'/{:who:}/interests',
		'/{:who:}/music',
		'/{:who:}/television',
		'/{:who:}/tagged',
		'/{:who:}/statuses',
		'/{:who:}/links',
		'/{:who:}/inbox',
		'/{:who:}/outbox',
		'/{:who:}/updates',
		'/{:who:}/apprequests',

		'/{:who:}/invited',
		'/{:who:}/picture',
		'/{:who:}/attending',
		'/{:who:}/maybe',
		'/{:who:}/noreply',
		'/{:who:}/declined',
		'/{:who:}?metadata=1',

		'/{:who:}/posts',

		'/{:who:}/likes',
		'/{:who:}/comments',
	);

	static $fields = array(
		/**
		 * create a new post on the given profile's feed/wall
		 **/
		'feed' => array(
			'message', 'picture', 'link', 'name', 'caption', 'description', 'source',
		),

		/**
		 * comment on the given post
		 **/
		'comments' => array(
			'message',
		),

		/**
		 * like the given post
		 **/
		'likes' => array(
		),

		/**
		 * write a note on the given profile
		 **/
		'notes' => array(
			'message', 'subject',
		),

		/**
		 * write a link on the given profile
		 **/
		'links' => array(
			'link', 'message',
		),

		/**
		 * create an event
		 **/
		'events' => array(
			'name', 'start_time', 'end_time',
		),

		/**
		 * attend the given event
		 **/
		'attending' => array(
		),

		/**
		 * maybe attend the given event
		 **/
		'maybe' => array(
		),

		/**
		 * decline the given event
		 **/
		'declined' => array(
		),

		/**
		 * create an album
		 **/
		'albums' => array(
			'name', 'message',
		),

		/**
		 * upload a photo to an album
		 **/
		'photos' => array(
			'message',
		),
	);

	public function &instance($config) {
		$args = func_get_args();

		$ref = new ReflectionClass(get_called_class());
		$instances =& $ref->newInstanceArgs((array)$args);

		return $instances;
	}

	public function __construct($config, $force_magic_quotes_gpc = 0) {
		parent::__construct($config);

		$force_magic_quotes_gpc && $this->magic_quotes_gpc();
	}

	public function &friends() {
		static $friends = null;
		($friends === null) && $friends =& new Scorpio_Api_Facebook_Friends(&$this);
		return $friends;
	}

	public function &session() {
		static $ret = null;
		($ret === null) && $ret =& $this->getSession();
		return $ret;
	}

	public function &getSession() {
		return parent::getSession();
	}

	public function &wall($who = 'me') {
		static $ret = array();

		if ($who == 'me' || !$who || $who === true || (string)$who == (string)$this->getUser()) {
			$who = 'me';
		}

		if (!isset($ret[$who]) || $ret[$who] === null) {
			$is_me = ($who == 'me' ? true : false);

			$ret[$who] =& new Scorpio_Api_Facebook_Wall(&$this, $is_me, (string)$who);
		}

		return $ret[$who];
	}

	public function &__get($key) {
		if ($key == 'friends') {
			return $this->friends();
		} elseif ($key == 'curl_opt') {
			return static::$CURL_OPTS;
		} elseif ($key == 'wall') {
			return $this->wall();
		} else {
			return $this->_opts[$key];
		}
	}

	public function getLoginUrl($params = array(), $req_perms = array()) {
		$params = is_array($params) ? $params : array();
		if (empty($req_perms) && (isset($params['req_perms']) || !empty($params['req_perms']))) {
			$req_perms = $params['req_perms'];
			unset($params['req_perms']);
		}

		if (!empty($req_perms)) {
			if ($req_perms == 'all') {
				$req_perms = array_filter(array_values(static::$req_perms));
			} elseif (is_string($req_perms)) {
				$req_perms = explode($req_perms, ',');
			} elseif ($this->_is_numkey_array($req_perms)) {
				$req_perms = array_values($req_perms);
			} elseif (!empty($req_perms) && is_array($req_perms)) {
				$req_perms = array_filter($req_perms);
				$req_perms = array_keys($req_perms);
			} else {
				$req_perms = array();
			}

			$req_perms = array_unique($req_perms);
			$params['req_perms'] = implode((array)$req_perms, ',');
		}

//		echo var_dump($_params);
//		exit();

		return parent::getLoginUrl($params);
	}

	public function getCurrentUrl($query = array()) {
		if (!empty($query) && is_array($query)) {
			$parts = parse_url(parent::getCurrentUrl());

			$agv = scotext::uri_parse_agv($parts['query']);
			$agv = scoarray::merge($agv, $query);

			foreach ($agv as $_k => $_v) {
				if ($_v === null) unset($agv[$_k]);
			}

			$parts['query'] = scotext::uri_build($agv, 1);

			return scotext::uri_build($parts);
		} else {
			return parent::getCurrentUrl();
		}
	}

	function _is_numkey_array($array) {
		return (range(0, count($array) - 1) == array_keys($array)) ? true : false;
	}

	public function &setSession($session=null, $write_cookie=null, $decode = null) {
		if ($write_cookie === null || $write_cookie == -1) $write_cookie = $this->useCookieSupport();

//		$this->magic_quotes_gpc();

		if ($decode) {
			$_session_ = null;
			if ($decode == 'array' || $decode == 'serialize' || $decode == 'unserialize') {
				$_session_ = @unserialize($session);
			} elseif ($decode == 'base64_decode' || $decode == 'base64') {
				$_session_ = @unserialize(@base64_decode($session));
			} else {
				$_session_ = @json_decode($session, true);
			}
			$session = $_session_;
		}

		return parent::setSession($session, $write_cookie);
	}

	function magic_quotes_gpc() {
		if (!get_magic_quotes_gpc()) {
			global $_REQUEST, $_COOKIE;
			$_REQUEST['session'] = stripslashes($_REQUEST['session']);

//			echo '<pre>session:<br>';
//			echo($_REQUEST['session']);
//
//			$session = $this->validateSessionObject($_REQUEST['session']);
//        	print_r($session);
//
//        	echo '<br>----------------</br>';

			if ($this->useCookieSupport()) {
				$cookieName = $this->getSessionCookieName();
        		if (isset($_COOKIE[$cookieName])) {
        			$_COOKIE[$cookieName] = stripslashes($_COOKIE[$cookieName]);
        		}

//        		echo($_COOKIE[$cookieName]);
//
//        		parse_str(trim($_COOKIE[$cookieName], '"'), $session);
//
//        		print_r($session);
//
//        		$session = $this->validateSessionObject($session);
//        		print_r($session);
			}



		}
	}
}

?>
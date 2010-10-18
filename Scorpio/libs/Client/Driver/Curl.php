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
	class Scorpio_Client_Driver_Curl extends Scorpio_Client_Driver_Curl_Core {}
}

class Scorpio_Client_Driver_Curl_Core extends Scorpio_Client_Driver {
	protected static $instances = null;

	// 取得構造物件
	public static function &instance($overwrite = false) {

		$args = func_get_args();
		array_shift($args);

		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstanceArgs((array)$args);
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstanceArgs((array)$args);
		}

		return static::$instances;
	}

	// 建立構造
	function __construct() {

		// make sure self::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;

			static::$default_options[CURLOPT_USERAGENT] = $_SERVER['HTTP_USER_AGENT'];
		}

//		$args = func_get_args();
//		list($url, $options) = $args;
//
//		$this->_attr['url']		= $url;
//		$this->_attr['options']	= array_merge($this->_attr['options'], (array)$options);

//		$this->_attr['retdata'] = $this->get($this->_attr['url'], $this->_attr['options']);

//		dexit($this->_attr['retdata']);

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	public static $default_options = array(
		CURLOPT_USERAGENT		=> 'Mozilla/5.0 (compatible; Scorpio +http://www.bluelovers.net/)',
		CURLOPT_CONNECTTIMEOUT	=> 5,
		CURLOPT_TIMEOUT			=> 5,

		CURLOPT_FOLLOWLOCATION	=> true,
		CURLOPT_RETURNTRANSFER	=> true,
		CURLOPT_AUTOREFERER		=> true,

		CURLOPT_SSL_VERIFYPEER	=> 0,

		CURLOPT_MAXREDIRS		=> 10,
		CURLOPT_ENCODING		=> '',
	);

	var $_attr = array(
		'options'	=> array(),
		'retdata'		=> array(),
	);

	function _url_exists($url, $allow302 = false, &$refurl = '') {
	    $hdrs = @get_headers($url, 1);

		if (is_array($hdrs)) {
		    preg_match('%^HTTP/\d+\.\d+\s+(\d{3})\s+.*$%', $hdrs[0], $result);
			$result = $result[1];
		} else {
			$result = 0;
		}

	    if ($allow302 && $result == 302 && $hdrs['Location']) {
			$refurl = $hdrs['Location'];
	    }

	    return (0 || ($allow302 && $result == 302) || ($result >= 200 && $result < 300)) ? $hdrs : false;
	}

	function get($url, $options = array()) {
		$options = array_merge(static::$default_options, (array)$options);

		$url = str_replace("&amp;", "&", urldecode($url));
		$remote = curl_init();

//		$options[CURLOPT_COOKIEJAR] = $this->cookies();
		$options[CURLOPT_URL] = $url;

		foreach ($options as $_k => $_v) {
			($_v !== null) && curl_setopt( $remote, $_k, $_v );
		}

//		curl_setopt_array($remote, $options);
//		ini_set('user_agent', $options[CURLOPT_USERAGENT]);

		$content = curl_exec($remote);
		$response = curl_getinfo($remote);

		if ($response['http_code'] AND $response['http_code'] < 200 OR $response['http_code'] > 299) {
//			$error = $content;
		} elseif ($content === FALSE) {
//			$error = curl_error($remote);
		}

		curl_close($remote);

		if ($headers = get_headers($url, 1)) {
			if ($response['http_code'] == 301 || $response['http_code'] == 302) {
				dexit(array($content, $response, $options, $headers, '888888888888888'.$headers['Location']));

//				foreach($headers as $value) {
					if ($headers['Location'])
						$value = trim($headers['Location']);

						return $this->get($value, $options);
//				}
			}
		}

		dexit(array($content, $response, $options, $headers));

		if (0 && $javascript_loop < 5 && (preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value))) {
			return $this->get($value[1], $options);
		} else {
			return array($content, $response);
		}
	}

	function cookies() {
		$cookie = tempnam ("/tmp", "CURLCOOKIE");
		return $cookie;
	}

	function options($url, $options = array()) {
		$options = array_merge(static::$default_options, (array)$this->_attr['options'], (array)$options);
		$url = str_replace("&amp;", "&", urldecode(trim($url)));

		$options[CURLOPT_URL] = $url;
		$options[CURLOPT_COOKIEJAR] = $this->cookies();

		return $options;
	}

	static function get_url( $url,  $javascript_loop = 0, $timeout = 5 ){

    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302)
    {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) )
        {
            foreach( $headers as $value )
            {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return static::get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if (  0 &&  ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
            $javascript_loop < 5
    )
    {
        return static::get_url( $value[1], $javascript_loop+1 );
    }
    else
    {
        return array( $content, $response );
    }
}

	function get_url2($url, $options = array()) {
		$options = $this->options($url, $options);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $options[CURLOPT_USERAGENT]);
		curl_setopt($ch, CURLOPT_URL, $options[CURLOPT_URL]);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $options[CURLOPT_COOKIEJAR]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $options[CURLOPT_FOLLOWLOCATION]);
		curl_setopt($ch, CURLOPT_ENCODING, $options[CURLOPT_ENCODING]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $options[CURLOPT_RETURNTRANSFER]);
		curl_setopt($ch, CURLOPT_AUTOREFERER, $options[CURLOPT_AUTOREFERER]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $options[CURLOPT_SSL_VERIFYPEER]); # required for https urls
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options[CURLOPT_CONNECTTIMEOUT]);
		curl_setopt($ch, CURLOPT_TIMEOUT, $options[CURLOPT_TIMEOUT]);
		curl_setopt($ch, CURLOPT_MAXREDIRS, $options[CURLOPT_MAXREDIRS]);
		$content = curl_exec($ch);
		$response = curl_getinfo($ch);
		curl_close ($ch);

		if ($response['http_code'] == 301 || $response['http_code'] == 302) {
			ini_set("user_agent", $options[CURLOPT_USERAGENT]);

			if ($headers = get_headers($response['url'])) {
				foreach($headers as $value) {
					if (substr(strtolower($value), 0, 9) == "location:")
						return $this->get_url(trim(substr($value, 9, strlen($value))), $options);
				}
			}
		}

		if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) && $javascript_loop < 5
				) {
			return $this->get_url($value[1], $options);
		} else {
			return array($content, $response);
		}
	}

}

?>
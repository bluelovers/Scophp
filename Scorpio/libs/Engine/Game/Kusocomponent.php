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
	class Scorpio_Engine_Game_Kusocomponent extends Scorpio_Engine_Game_Kusocomponent_Core {
	}
}

/**
 * 分析你的成分
 *
 * http://w3.nctu.edu.tw/~u8912009/mis/component_check.rar
 * http://w3.nctu.edu.tw/~u8912009/mis/component_check_4.2.rar
 */
class Scorpio_Engine_Game_Kusocomponent_Core {

	//成份列表
	//重複出現的東西，出現率會比較高。總長度建議不要超過255。
	var $component = array();

	var $decimals = 2;

	//傳入整數(或整數字串)X，pointer會向後移動X，然後傳回該位置的元素名。
	//到陣列結尾就重頭開始
	var $pointer = 0;

	function __construct($component = array()) {
		$this->component = $component;
	}

	function reset() {
		$this->pointer = 0;
	}

	function clear($input_string) {
		return trim($input_string, "\t");
	}

	function hash($input_string) {
		return md5($input_string);
	}

	function chk_name($name) {
		$name = $this->clear($name);

		//對名字長度作出限制，以免Hash跑太久
		if (empty($name)) {
			return $this->_error('請輸入名字');
		} elseif (strlen($name) > 256) {
			return $this->_error('輸入的名字太長了');
		}

		return $name;
	}

	function setName($name, $component = null) {

		$input_string = $this->chk_name($name);

		$input_hash = $this->hash($input_string);

		$hase_length = strlen($input_hash);
		$chunks = str_split($input_hash, 2);

		$component !== null && $this->component = $component;

		$elist = array();

		// $elist[成份名稱]=成份含量
		$total_quantity = 0; //成份總量
		for ($i = 0; $i < count($chunks); $i += 2) {
			$current_component = $this->get_elem($this->get_idx($chunks[$i]));

			$current_quantity = $this->get_idx($chunks[$i + 1]);

			//三次方，將大量成份跟少量成分的距離拉大
			$current_quantity = bcpow($current_quantity, 3, $this->decimals);

			$total_quantity += $current_quantity;

			if (isset($elist[$current_component])) {
				$elist[$current_component] += $current_quantity;
			} else {
				$elist[$current_component] = $current_quantity;
			}
		}

		$elist2 = array();

		$min = '0.' . str_pad(9, $this->decimals, '0', STR_PAD_LEFT);

		//sort
		arsort($elist);
		foreach ($elist as $k => $v) {
			$percent = number_format(100 * $elist[$k] / $total_quantity, $this->decimals);

			//太少的東西就不顯示了
			if (bccomp($percent, $min, $this->decimals) > 0) {
				$elist2[$k] = array('item' => $k, 'percent' => $percent, 'seed' => $v, );
			}
		}

		return $elist2;
	}

	function get_idx($id) {
		eval("\$ret = 0x{$id};");

		return $ret;
	}

	function get_elem($id) {
		if (!is_numeric($id))
			return $this->_error('Error!');
		else {
			$this->pointer += $id;

			$this->pointer %= count($this->component);
			return $this->component[$this->pointer];
		}
	}

	function _error($string) {
		return $string;
	}

}

/*

$elements=array(
'屍毒',
'御宅氣',
'高手高手高高手',
'雜魚',
'高頻雜訊',
'黑暗',
'死靈怨影',
'光',
'性慾',
'心中的翡翠森林',
'心中的斷背山',
'大宇宙的意志',
'燃燒的小宇宙',
'反物質',
'三鋰水晶',
'空間扭曲',
'時空斷層',
'微型黑洞',
'微波雷射',
'化屍水',
'王水',
'海水',
'一江春水',
'花癡',
'夢',
'烈日之心',
'友愛',
'愛心光束',
'命運的相逢',
'巨大蘿蔔',
'高張力鋼',
'米諾夫斯基粒子',
'G3毒氣',
'三倍速',
'彈幕',
'沙林毒氣',
'新人類',
'恨',
'鬼東西',
'歌聲',
'腦殘',
'墮落',
'飢渴',
'戀童癖',
'自戀',
'戀父情結',
'戀母情結',
'戀兄情結',
'戀妹情結',
'愛','愛','愛','愛',
'沒創意',
'髒空氣',
'不良思想',
'反動思想',
'細肩帶小女孩不加辣',
'細肩帶小男孩不加辣',
'渣渣',
'成為豆腐的覺悟',
'撞豆腐自殺的勇氣',
'被受害人折斷的決心',
'義理巧克力',
'星之雨',
'腦麻',
'變態',
'嘴砲',
'信念',
'微妙',
'莫名奇妙',
'巨大怪獸',
'人體暖爐',
'智慧',
'天然呆',
'生命之水',
'天邊一朵雲',
'糟糕',
'心機',
'超合金',
'乙醯膽鹼',
'氫氟酸',
'絨毛',
'碎碎念',
'怨念',
'宿便','宿便',
'毒電波','毒電波','毒電波','毒電波',
'正義之心',
'腦漿',
'膿','膿','膿',
'海之冰',
'狗血',
'核子反應原料',
'反應爐冷卻水',
'高性能炸藥',
'對艦大型雷爆彈',
'國造六六火箭彈',
'超音波',
'觀世音',
'天下第一舉世無雙絕對無敵真正非常超越超級震古鑠今空前絕後刀槍不入無堅不摧無所不能好厲害',
'謎'
);

$d = new Scorpio_Engine_Game_Kusocomponent_Core($elements);

var_dump($d->setName('妖蛛蛛'));

0.01array(8) {
  ["三倍速"]=>
  array(3) {
    ["item"]=>
    string(9) "三倍速"
    ["percent"]=>
    string(5) "37.84"
    ["seed"]=>
    string(8) "10648000"
  }
  ["超合金"]=>
  array(3) {
    ["item"]=>
    string(9) "超合金"
    ["percent"]=>
    string(5) "35.82"
    ["seed"]=>
    string(8) "10077696"
  }
  ["命運的相逢"]=>
  array(3) {
    ["item"]=>
    string(15) "命運的相逢"
    ["percent"]=>
    string(5) "17.77"
    ["seed"]=>
    string(7) "5000211"
  }
  ["高頻雜訊"]=>
  array(3) {
    ["item"]=>
    string(12) "高頻雜訊"
    ["percent"]=>
    string(4) "2.95"
    ["seed"]=>
    string(6) "830584"
  }
  ["心中的斷背山"]=>
  array(3) {
    ["item"]=>
    string(18) "心中的斷背山"
    ["percent"]=>
    string(4) "2.42"
    ["seed"]=>
    string(6) "681472"
  }
  ["黑暗"]=>
  array(3) {
    ["item"]=>
    string(6) "黑暗"
    ["percent"]=>
    string(4) "1.38"
    ["seed"]=>
    string(6) "389017"
  }
  ["星之雨"]=>
  array(3) {
    ["item"]=>
    string(9) "星之雨"
    ["percent"]=>
    string(4) "1.12"
    ["seed"]=>
    string(6) "314432"
  }
  ["微波雷射"]=>
  array(3) {
    ["item"]=>
    string(12) "微波雷射"
    ["percent"]=>
    string(4) "0.69"
    ["seed"]=>
    string(6) "195112"
  }
}

*/

?>
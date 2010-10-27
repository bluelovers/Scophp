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
	class Scorpio_Math_WanNianLi extends Scorpio_Math_WanNianLi_Core {
	}
}

class Scorpio_Math_WanNianLi_Core {
	static $startYear = 1901;
	static $jieQiDb = array('96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69',
		'78', '77', //1901
		'96', 'A4', '96', '96', '97', '87', '79', '79', '79', '69', '78', '78', //1902
		'96', 'A5', '87', '96', '87', '87', '79', '69', '69', '69', '78', '78', //1903
		'86', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '79', '78', '87', //1904
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1905
		'96', 'A4', '96', '96', '97', '97', '79', '79', '79', '69', '78', '78', //1906
		'96', 'A5', '87', '96', '87', '87', '79', '69', '69', '69', '78', '78', //1907
		'86', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1908
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1909
		'96', 'A4', '96', '96', '97', '97', '79', '79', '79', '69', '78', '78', //1910
		'96', 'A5', '87', '96', '87', '87', '79', '69', '69', '69', '78', '78', //1911
		'86', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1912
		'95', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1913
		'96', 'B4', '96', 'A6', '97', '97', '79', '79', '79', '69', '78', '78', //1914
		'96', 'A5', '97', '96', '97', '87', '79', '79', '69', '69', '78', '78', //1915
		'96', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1916
		'95', 'B4', '96', 'A6', '96', '97', '78', '79', '78', '69', '78', '87', //1917
		'96', 'B4', '96', 'A6', '97', '97', '79', '79', '79', '69', '78', '77', //1918
		'96', 'A5', '97', '96', '97', '87', '79', '79', '69', '69', '78', '78', //1919
		'96', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1920
		'95', 'B4', '96', 'A5', '96', '97', '78', '79', '78', '69', '78', '87', //1921
		'96', 'B4', '96', 'A6', '97', '97', '79', '79', '79', '69', '78', '77', //1922
		'96', 'A4', '96', '96', '97', '87', '79', '79', '69', '69', '78', '78', //1923
		'96', 'A5', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1924
		'95', 'B4', '96', 'A5', '96', '97', '78', '79', '78', '69', '78', '87', //1925
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1926
		'96', 'A4', '96', '96', '97', '87', '79', '79', '79', '69', '78', '78', //1927
		'96', 'A5', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1928
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1929
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1930
		'96', 'A4', '96', '96', '97', '87', '79', '79', '79', '69', '78', '78', //1931
		'96', 'A5', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1932
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1933
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1934
		'96', 'A4', '96', '96', '97', '97', '79', '79', '79', '69', '78', '78', //1935
		'96', 'A5', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1936
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1937
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1938
		'96', 'A4', '96', '96', '97', '97', '79', '79', '79', '69', '78', '78', //1939
		'96', 'A5', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1940
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1941
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1942
		'96', 'A4', '96', '96', '97', '97', '79', '79', '79', '69', '78', '78', //1943
		'96', 'A5', '96', 'A5', 'A6', '96', '88', '78', '78', '78', '87', '87', //1944
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1945
		'95', 'B4', '96', 'A6', '97', '97', '78', '79', '78', '69', '78', '77', //1946
		'96', 'B4', '96', 'A6', '97', '97', '79', '79', '79', '69', '78', '78', //1947
		'96', 'A5', 'A6', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //1948
		'A5', 'B4', '96', 'A5', '96', '97', '88', '79', '78', '79', '77', '87', //1949
		'95', 'B4', '96', 'A5', '96', '97', '78', '79', '78', '69', '78', '77', //1950
		'96', 'B4', '96', 'A6', '97', '97', '79', '79', '79', '69', '78', '78', //1951
		'96', 'A5', 'A6', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //1952
		'A5', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1953
		'95', 'B4', '96', 'A5', '96', '97', '78', '79', '78', '68', '78', '87', //1954
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1955
		'96', 'A5', 'A5', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //1956
		'A5', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1957
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1958
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1959
		'96', 'A4', 'A5', 'A5', 'A6', '96', '88', '88', '88', '78', '87', '87', //1960
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1961
		'96', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1962
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1963
		'96', 'A4', 'A5', 'A5', 'A6', '96', '88', '88', '88', '78', '87', '87', //1964
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1965
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1966
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1967
		'96', 'A4', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //1968
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1969
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1970
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '79', '69', '78', '77', //1971
		'96', 'A4', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //1972
		'A5', 'B5', '96', 'A5', 'A6', '96', '88', '78', '78', '78', '87', '87', //1973
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1974
		'96', 'B4', '96', 'A6', '97', '97', '78', '79', '78', '69', '78', '77', //1975
		'96', 'A4', 'A5', 'B5', 'A6', 'A6', '88', '89', '88', '78', '87', '87', //1976
		'A5', 'B4', '96', 'A5', '96', '96', '88', '88', '78', '78', '87', '87', //1977
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '78', '87', //1978
		'96', 'B4', '96', 'A6', '96', '97', '78', '79', '78', '69', '78', '77', //1979
		'96', 'A4', 'A5', 'B5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //1980
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '77', '87', //1981
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1982
		'95', 'B4', '96', 'A5', '96', '97', '78', '79', '78', '69', '78', '77', //1983
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '87', //1984
		'A5', 'B4', 'A6', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //1985
		'A5', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //1986
		'95', 'B4', '96', 'A5', '96', '97', '88', '79', '78', '69', '78', '87', //1987
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //1988
		'A5', 'B4', 'A5', 'A5', 'A6', '96', '88', '88', '88', '78', '87', '87', //1989
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '79', '77', '87', //1990
		'95', 'B4', '96', 'A5', '86', '97', '88', '78', '78', '69', '78', '87', //1991
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //1992
		'A5', 'B3', 'A5', 'A5', 'A6', '96', '88', '88', '88', '78', '87', '87', //1993
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1994
		'95', 'B4', '96', 'A5', '96', '97', '88', '76', '78', '69', '78', '87', //1995
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //1996
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //1997
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //1998
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //1999
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2000
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2001
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //2002
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //2003
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2004
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2005
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2006
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '69', '78', '87', //2007
		'96', 'B4', 'A5', 'B5', 'A6', 'A6', '87', '88', '87', '78', '87', '86', //2008
		'A5', 'B3', 'A5', 'B5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2009
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2010
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '78', '87', //2011
		'96', 'B4', 'A5', 'B5', 'A5', 'A6', '87', '88', '87', '78', '87', '86', //2012
		'A5', 'B3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '87', //2013
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2014
		'95', 'B4', '96', 'A5', '96', '97', '88', '78', '78', '79', '77', '87', //2015
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '87', '88', '87', '78', '87', '86', //2016
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '87', //2017
		'A5', 'B4', 'A6', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2018
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '79', '77', '87', //2019
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '86', //2020
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2021
		'A5', 'B4', 'A5', 'A5', 'A6', '96', '88', '88', '88', '78', '87', '87', //2022
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '79', '77', '87', //2023
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '96', //2024
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2025
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2026
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //2027
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '96', //2028
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2029
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2030
		'A5', 'B4', '96', 'A5', '96', '96', '88', '78', '78', '78', '87', '87', //2031
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '96', //2032
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '88', '88', '88', '78', '87', '86', //2033
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '78', '88', '78', '87', '87', //2034
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2035
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '96', //2036
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '86', //2037
		'A5', 'B3', 'A5', 'A5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2038
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2039
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '78', '87', '96', //2040
		'A5', 'C3', 'A5', 'B5', 'A5', 'A6', '87', '88', '87', '78', '87', '86', //2041
		'A5', 'B3', 'A5', 'B5', 'A6', 'A6', '88', '88', '88', '78', '87', '87', //2042
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2043
		'95', 'B4', 'A5', 'B4', 'A5', 'A6', '97', '87', '87', '88', '87', '96', //2044
		'A5', 'C3', 'A5', 'B4', 'A5', 'A6', '87', '88', '87', '78', '87', '86', //2045
		'A5', 'B3', 'A5', 'B5', 'A6', 'A6', '87', '88', '88', '78', '87', '87', //2046
		'A5', 'B4', '96', 'A5', 'A6', '96', '88', '88', '78', '78', '87', '87', //2047
		'95', 'B4', 'A5', 'B4', 'A5', 'A5', '97', '87', '87', '88', '86', '96', //2048
		'A4', 'C3', 'A5', 'A5', 'A5', 'A6', '97', '87', '87', '78', '87', '86', //2049
		'A5', 'C3', 'A5', 'B5', 'A6', 'A6', '87', '88', '78', '78', '87', '87'); //2050
	// 某年第N個節氣的交氣日期
	// 從1起小寒

	static function JiaoQiDay($yr, $n) {
		$flag = '';
		if ($n % 2 == 0) //雙數
			{
			$weizhi = ($yr - Scorpio_Math_WanNianLi::$startYear) * 12 + $n / 2 - 1;
			$flag = Scorpio_Math_WanNianLi::$jieQiDb[$weizhi];
			$flag = substr($flag, 1, 1);
			$flag = str_replace('A', '10', $flag);
			$flag = str_replace('B', '11', $flag);
			$flag = str_replace('C', '12', $flag);
			return 15 + (int)$flag;
		}
		//單數
		$weizhi = ($yr - Scorpio_Math_WanNianLi::$startYear) * 12 + ($n + 1) / 2 - 1;
		$flag = Scorpio_Math_WanNianLi::$jieQiDb[$weizhi];
		$flag = substr($flag, 0, 1);
		$flag = str_replace('A', '10', $flag);
		$flag = str_replace('B', '11', $flag);
		$flag = str_replace('C', '12', $flag);
		return 15 - (int)$flag;
	}

	/// 某年第N個節氣的交氣日期
	static function JiaoQiDate($year, $n) {
		$month = 1;
		$month = round((($n + 1) / 2), 0);
		return strval($year) . '-' . strval($month) . '-' . strval(Scorpio_Math_WanNianLi::JiaoQiDay($year,
			$n));
	}
}

?>
<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Yaml extends Symfony_Component_Yaml_Yaml
{

	const INLINE = 6;

	public static function load($file, $enablePhpParsing = false)
	{
		if (Sco_File_Helper::is_resource_file($file))
		{
			$data = Sco_File_Helper::fp_get_contents($file);
		}
		elseif (is_readable($file))
		{
			$data = $file;
		}
		else
		{
			return false;
		}

		$yaml = self::parse($data);

		return $yaml;
	}

	public static function save($file, $data, $inline = self::INLINE)
	{
		$dump = self::dump($data, $inline);

		if (Sco_File_Helper::is_resource_file($file))
		{
			$ret = Sco_File_Helper::fp_put_contents($file, $dump, LOCK_EX);
		}
		else
		{
			$ret = file_put_contents($file, $dump, LOCK_EX);
		}

		return $ret;
	}

	public static function dump($array, $inline = self::INLINE)
	{
		if (is_object($array) && method_exists($array, 'toYaml')) {
            return $array->toYaml();
        }

		return parent::dump($array, $inline);
	}

}


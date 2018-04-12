<?php
namespace Lib\Helpers;

class StringUtils {
	public static function wrapWithSpaces(string $str):string {
		return ' ' . $str . ' ';
	}
	public static function getEmptyIfNull(array $arr, string $key) {
		return isset($arr[$key]) ? $arr[$key] : '';
	}
}
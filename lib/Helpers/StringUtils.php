<?php
namespace Lib\Helpers;

class StringUtils {

	/**
	 * helps concatenating multiple sql OR/AND where clauses
	 *
	 * @param      string  $str
	 *
	 * @return     string  $str with spaces on both sides
	 */
	public static function wrapWithSpaces(string $str):string {
		return ' ' . $str . ' ';
	}
	/**
	 * returns an empty string if key doesn't exist in the array.
	 *
	 * @param      array   $arr
	 * @param      string  $key
	 *
	 * @return     string
	 */
	public static function getEmptyIfNull(array $arr, string $key):string {
		return isset($arr[$key]) ? $arr[$key] : '';
	}
}
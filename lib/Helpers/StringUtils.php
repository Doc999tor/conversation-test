<?php
namespace Lib\Helpers;

class StringUtils {
	public static function wrapWithSpaces(string $str):string {
		return ' ' . $str . ' ';
	}
}
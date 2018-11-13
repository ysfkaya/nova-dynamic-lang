<?php

namespace Ysfkaya\NovaDynamicLang\Exceptions;

use Exception;

class InvalidJsonPathException extends Exception
{
	public static function setPath($path = null, $undefined = false)
	{
		if ($undefined) {
			$message = 'The json path is undefined from "nova-dynamic-lang.php"';
		} else {
			$message = 'Not found a json file in '.$path;
		}

		return new self($message);
	}
}
<?php

namespace Ysfkaya\NovaDynamicLang\Facades;

use Illuminate\Support\Facades\Facade;
use Ysfkaya\NovaDynamicLang\Language as BaseLang;

class Language extends Facade
{
	protected static function getFacadeAccessor()
	{
		return BaseLang::class;
	}
}
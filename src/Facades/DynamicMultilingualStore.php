<?php

namespace Ysfkaya\NovaDynamicLang\Facades;

use Illuminate\Support\Facades\Facade;
use Ysfkaya\NovaDynamicLang\Manager;

class DynamicMultilingualStore extends Facade
{
	protected static function getFacadeAccessor()
	{
		return Manager::class;
	}
}
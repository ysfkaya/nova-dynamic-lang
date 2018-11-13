<?php

namespace Ysfkaya\NovaDynamicLang\Contracts;

interface Store
{
	public function all();

	public function withFields(array $fields = []);
}
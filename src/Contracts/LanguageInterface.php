<?php
/**
 * Created by PhpStorm.
 * User: Yusuf
 * Date: 13/11/2018
 * Time: 15:54
 */

namespace Ysfkaya\NovaDynamicLang\Contracts;

interface LanguageInterface
{
	public function create($code, $fields = []);

	public function update($code, $fields = []);

	public function delete($code);

	public function all();

	public function exists($lang);
}
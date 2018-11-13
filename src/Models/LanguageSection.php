<?php

namespace Ysfkaya\NovaDynamicLang\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageSection extends Model
{
	protected $table = 'language_sections';

	protected $fillable = [
		'label',
		'value',
		'code'
	];
}
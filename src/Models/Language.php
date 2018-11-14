<?php

namespace Ysfkaya\NovaDynamicLang\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
	protected $table = 'languages';

	protected $with = ['flag'];

	protected $fillable = [
		'code',
		'name',
		'short_name',
		'fields',
		'status',
	];

	protected $casts = [
		'fields' => 'collection',
		'status' => 'boolean',
	];

	public function flag()
	{
		return $this->hasOne(LanguageFlag::class);
	}

	protected static function boot()
	{
		parent::boot();

		self::deleting(function ($model) {
			$model->flag->delete();
		});
	}
}
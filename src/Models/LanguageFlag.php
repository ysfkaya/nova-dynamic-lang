<?php

namespace Ysfkaya\NovaDynamicLang\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LanguageFlag extends Model
{
	protected $table = 'language_flags';

	protected $fillable = [
		'language_id',
		'file_name',
		'original_name',
		'width',
		'height',
		'size',
	];

	protected $appends = [
		'thumb_url',
	];

	protected static function boot()
	{
		parent::boot();

		self::deleting(function ($model) {
			Storage::disk(config('nova-dynamic-lang.disk', 'public'))->delete('flags/'.$model->file_name);
		});
	}

	public function getThumbUrlAttribute()
	{
		return Storage::disk(config('nova-dynamic-lang.disk', 'public'))->url('flags/'.$this->attributes['file_name']);
	}
}
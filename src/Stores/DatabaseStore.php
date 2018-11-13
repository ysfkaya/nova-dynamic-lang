<?php

namespace Ysfkaya\NovaDynamicLang\Stores;

use Ysfkaya\NovaDynamicLang\Models\Language;
use Ysfkaya\NovaDynamicLang\Models\LanguageSection;

class DatabaseStore extends Store
{
	/**
	 * @var \Ysfkaya\NovaDynamicLang\Models\LanguageSection
	 */
	protected $model;

	public $cachedAll = null;

	public function __construct(LanguageSection $model)
	{
		$this->model = $model;
	}

	public function all()
	{
		if (is_null($this->cachedAll)) {
			$this->cachedAll = $this->model->all(['label', 'value', 'code']);
		}

		return $this->cachedAll;
	}

	public function withFields(array $fields = [])
	{
		$items = $this->model->all(['label', 'value', 'code'])->toArray();

		$this->cachedAll = collect($items)->map(function ($item) use ($fields) {
			$newItem = $item;

			if (array_key_exists($item['code'], $fields)) {
				$newItem['value'] = $fields[ $item['code'] ];
			}

			return $this->parseForNova(array_values($newItem));
		});

		return $this;
	}
}
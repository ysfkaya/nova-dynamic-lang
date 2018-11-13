<?php

namespace Ysfkaya\NovaDynamicLang\Stores;

use Illuminate\Support\Collection;
use Ysfkaya\NovaDynamicLang\Contracts\Store as StoreContract;

abstract class Store implements StoreContract
{
	protected function parseForNova($items)
	{
		if (is_array($items)) {
			$items = collect($items);
		}

		return $items->map(function ($item) {
			return [
				'name' => $item[0],
				'value' => $item[1],
				'code' => $item[2],
			];
		});
	}

	protected function tap($value, callable $callback)
	{
		return $callback($value);
	}

	protected function collectionFields($fields)
	{
		if (empty($fields) || ! property_exists($this, 'collection')) {
			return;
		}

		$this->collection = $this->collection->map(function ($item) use ($fields) {
			$newItem = $item;

			if (array_key_exists($item[2], $fields)) {
				$newItem[1] = $fields[ $item[2] ];
			}

			return $newItem;
		});
	}
}
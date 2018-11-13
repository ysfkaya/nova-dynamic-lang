<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang\Stores;

use Illuminate\Support\Collection;

class ArrayStore extends Store
{
	/**
	 * @var Collection
	 */
	protected $collection;

	/**
	 * ArrayStore constructor.
	 *
	 * @param array $fields
	 */
	public function __construct(array $fields = [])
	{
		$this->collection = collect($fields);
	}

	public function all()
	{
		$collection = $this->collection->filter(function ($item) {
			return count($item) === 3;
		});

		return $this->parseForNova($collection);
	}

	public function withFields(array $fields = [])
	{
		$this->collectionFields($fields);

		return $this;
	}
}
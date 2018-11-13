<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang\Stores;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Ysfkaya\NovaDynamicLang\Exceptions\InvalidJsonPathException;

class JsonStore extends Store
{
	/**
	 * @var null
	 */
	protected $path;

	/**
	 * @var Collection
	 */
	protected $collection;

	/**
	 * JsonStore constructor.
	 *
	 * @param null $path
	 *
	 * @throws \Ysfkaya\NovaDynamicLang\Exceptions\InvalidJsonPathException
	 */
	public function __construct($path = null)
	{
		$this->setPath($path);
		$this->handleJson();
	}

	private function setPath($path = null)
	{
		if (empty($path)) {
			throw InvalidJsonPathException::setPath($path, true);
		}

		if ( ! File::exists($path) || File::extension($path) !== 'json') {
			throw InvalidJsonPathException::setPath($path);
		}

		$this->path = $path;
	}

	private function handleJson()
	{
		$contents = File::get($this->path, true);

		$arr = json_decode($contents, true);

		$this->collection = Collection::make($arr);
	}

	public function all()
	{
		return $this->tap($this->collection->get('fields'), function ($items) {
			$collection = collect($items)->filter(function ($item) {
				return count($item) === 3;
			});

			return $this->parseForNova($collection);
		});
	}

	public function withFields(array $fields = [])
	{
		$this->collectionFields($fields,$this->collection);

		return $this;
	}
}
<?php

namespace Ysfkaya\NovaDynamicLang;

use InvalidArgumentException;
use LogicException;
use Ysfkaya\NovaDynamicLang\Stores\ArrayStore;
use Ysfkaya\NovaDynamicLang\Stores\DatabaseStore;
use Ysfkaya\NovaDynamicLang\Stores\JsonStore;

/**
 * Class DynamicMultilingual
 *
 * @package Ysfkaya\DynamicMultilingual
 */
class Manager
{
	/**
	 * The application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * The array of resolved language stores.
	 *
	 * @var array
	 */
	protected $stores = [];


	/**
	 * Create a new Language manager instance.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 *
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Get a language store instance by name.
	 *
	 * @param  string|null $name
	 *
	 * @return mixed|\Ysfkaya\NovaDynamicLang\Contracts\Store
	 */
	public function store($name = null)
	{
		$name = $name ?: $this->getDefaultDriver();

		return $this->stores[ $name ] = $this->get($name);
	}

	/**
	 * Get a language driver instance.
	 *
	 * @param  string|null $driver
	 *
	 * @return mixed
	 */
	public function driver($driver = null)
	{
		return $this->store($driver);
	}

	/**
	 * Attempt to get the store from the local language store.
	 *
	 * @param  string $name
	 *
	 * @return mixed|\Ysfkaya\NovaDynamicLang\Contracts\Store
	 */
	protected function get($name)
	{
		return $this->stores[ $name ] ?? $this->resolve($name);
	}

	/**
	 * Resolve the given store.
	 *
	 * @param  string $name
	 *
	 * @return mixed|\Ysfkaya\NovaDynamicLang\Contracts\Store
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function resolve($name)
	{
		$config = $this->getConfig($name);

		if (is_null($config)) {
			throw new InvalidArgumentException("Language store [{$name}] is not defined.");
		}

		$driverMethod = 'create'.ucfirst($config['driver']).'Driver';

		if (method_exists($this, $driverMethod)) {
			return $this->{$driverMethod}($config);
		} else {
			throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
		}
	}

	/**
	 * Create an instance of the Array driver.
	 *
	 * @param  array $config
	 *
	 * @return \Ysfkaya\NovaDynamicLang\Stores\ArrayStore
	 */
	protected function createArrayDriver(array $config)
	{
		$fields = $config['fields'] ?? [];

		return new ArrayStore($fields);
	}

	/**
	 * Create an instance of the Json  driver.
	 *
	 * @param  array $config
	 *
	 * @return \Ysfkaya\NovaDynamicLang\Stores\JsonStore
	 * @throws \Ysfkaya\NovaDynamicLang\Exceptions\InvalidJsonPathException
	 */
	protected function createJsonDriver(array $config)
	{
		$path = $config['path'] ?? null;

		return new JsonStore($path);
	}

	/**
	 * Create an instance of the Database driver.
	 *
	 * @param  array $config
	 *
	 * @return \Ysfkaya\NovaDynamicLang\Stores\DatabaseStore
	 */
	protected function createDatabaseDriver(array $config)
	{
		$model = $config['model'];

		if ( ! is_string($model)) {
			throw new LogicException("The $model is not string");
		}

		$model = $this->app->make($model);

		return new DatabaseStore($model);
	}

	/**
	 * Get the field configuration.
	 *
	 * @param  string $name
	 *
	 * @return array
	 */
	protected function getConfig($name)
	{
		return $this->app['config']["nova-dynamic-lang.sections.stores.{$name}"];
	}

	/**
	 * Get the default field driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver()
	{
		return $this->app['config']['nova-dynamic-lang.sections.default'];
	}

	/**
	 * Dynamically call the default driver instance.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 *
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return $this->store()->$method(...$parameters);
	}
}

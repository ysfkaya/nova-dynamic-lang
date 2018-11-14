<?php

namespace Ysfkaya\NovaDynamicLang;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Ysfkaya\NovaDynamicLang\Http\Middleware\Authorize;

class NovaDynamicLangProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-dynamic-lang');

		$this->loadPublishes();

		$this->app->booted(function () {
			$this->routes();
		});

		Nova::serving(function (ServingNova $event) {
			//
		});
	}

	/**
	 * Register the tool's routes.
	 *
	 * @return void
	 */
	protected function routes()
	{
		if ($this->app->routesAreCached()) {
			return;
		}

		Route::middleware(['nova', Authorize::class])
			->namespace('Ysfkaya\\NovaDynamicLang\\Http\\Controllers')
			->prefix('nova-vendor/nova-dynamic-lang')
			->group(__DIR__.'/../routes/api.php');
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/nova-dynamic-lang.php', 'nova-dynamic-lang');

		$this->app->singleton(Manager::class, function ($app) {
			return new Manager($app);
		});

		$this->app->singleton(Language::class, function ($app) {
			return new LanguageAdapter(new Language);
		});
	}

	protected function loadPublishes()
	{
		$this->publishes([
			__DIR__.'/../config/nova-dynamic-lang.php' => config_path('nova-dynamic-lang.php'),
		], 'config');

		$this->publishes([
			__DIR__.'/../database/migrations/create_language_tables.php.stub' => database_path('migrations/'
			                                                                                           .date('Y_m_d_His', time())
			                                                                                           .'_create_language_tables.php'),
		], 'migrations');
	}
}

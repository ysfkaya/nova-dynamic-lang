<?php /** @noinspection PhpIncludeInspection */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang;

use Illuminate\Support\Facades\File;
use Ysfkaya\NovaDynamicLang\Contracts\LanguageInterface;

class Language implements LanguageInterface
{
	public function delete($code)
	{
		$lang = $this->firstByCode($code);

		return File::delete($lang['file_path']);
	}

	public function update($code, $fields = [])
	{
		$lang_path = resource_path('lang');

		$path = $lang_path.'/'.$code;

		$file_path = $path.'/'.config('nova-dynamic-lang.file_name', 'frontend').'.php';

		if ( ! File::exists($file_path)) {
			abort(404);
		}

		$fields = $this->mapFields($fields);

		return File::put($file_path, $fields);
	}

	public function create($code, $fields = [])
	{
		$lang_path = resource_path('lang');

		$path = $lang_path.'/'.$code;

		$file_path = $path.'/'.config('nova-dynamic-lang.file_name', 'frontend').'.php';

		if ( ! File::isDirectory($path)) {
			File::makeDirectory($path, 0755);
		}

		return File::put($file_path, $this->mapFields($fields));
	}

	public function mapFields($fields = [])
	{
		$stub = __DIR__.'/../stubs/lang.php.stub';

		$content = File::get($stub);

		if (empty($fields)) {
			$newContent = null;
		} else {
			$newContent = $this->handleFields($fields);
		}

		return str_replace('{{ replace }}', $newContent, $content);
	}

	public function handleFields($fields)
	{
		$string = '';

		foreach ($fields as $field) {
			$string .= '"'.$field['code'].'" => "'.$field['value'].'",';
			$string .= "\n";
		}

		return $string;
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function getDirectories()
	{
		return collect(File::directories(resource_path('lang')))->filter(function ($path) {
			$lang = last(explode('\\', $path));

			return $lang !== 'vendor';
		});
	}

	/**
	 * @return array
	 */
	public function getDefaultLanguages()
	{
		$pathFromConfig = config('nova-dynamic-lang.default_languages_path', null);

		if ($pathFromConfig && File::exists($pathFromConfig) && File::extension($pathFromConfig) === 'json') {
			$contents = File::get($pathFromConfig);
		} else {
			$contents = File::get(__DIR__.'/../resources/data/languages.json');
		}

		return json_decode($contents, true);
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function all()
	{
		$languages = collect();

		$directories = self::getDirectories();

		$defaultLanguages = self::getDefaultLanguages();

		$file = config('nova-dynamic-lang.file_name', 'frontend');

		$directories->map(function ($directory) use ($file, $defaultLanguages) {
			$lang = last(explode('\\', $directory));

			$languages = array_pluck($defaultLanguages, 'label', 'code');

			$label = $languages[ $lang ] ?? null;

			return [
				'lang' => $lang,
				'label' => $label,
				'lang_path' => $directory.'/'.$file.'.php',
			];
		})->each(function ($directory) use ($languages) {
			if (File::exists($directory['lang_path'])) {
				$languages->push([
					'code' => $directory['lang'],
					'label' => $directory['label'],
				]);
			}
		});

		return $languages;
	}

	public function firstByCode($lang)
	{
		if ( ! $this->exists($lang)) {
			abort(404);
		}

		$directories = self::getDirectories();

		$defaultLanguages = self::getDefaultLanguages();

		$file = config('nova-dynamic-lang.file_name', 'frontend');

		return $directories->filter(function ($directory) use ($file, $lang) {
			$directoryLang = last(explode('\\', $directory));

			return $directoryLang === $lang;
		})->map(function ($directory) use ($file, $defaultLanguages) {
			$lang = last(explode('\\', $directory));

			$languages = array_pluck($defaultLanguages, 'label', 'code');

			$label = $languages[ $lang ] ?? null;

			$file_path = $directory.'/'.$file.'.php';

			$fields = require_once $file_path;

			return [
				'code' => $lang,
				'label' => $label,
				'fields' => $fields,
				'file_path' => $file_path,
			];
		})->first();
	}

	public function exists($lang)
	{
		$directories = self::getDirectories();

		$file = config('nova-dynamic-lang.file_name', 'frontend');

		return $directories->filter(function ($directory) use ($file) {
			return File::exists($directory.'/'.$file.'.php');
		})->map(function ($directory) {
			return last(explode('\\', $directory));
		})->flip()->has($lang);
	}
}
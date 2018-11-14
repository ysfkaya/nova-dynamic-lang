<?php /** @noinspection PhpIncludeInspection */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ysfkaya\NovaDynamicLang\Contracts\LanguageInterface;
use Ysfkaya\NovaDynamicLang\Models\Language as Model;
use Ysfkaya\NovaDynamicLang\Models\LanguageFlag;

class LanguageAdapter
{
	/**
	 * @var \Ysfkaya\NovaDynamicLang\Contracts\LanguageInterface
	 */
	protected $language;

	/**
	 * @var \Ysfkaya\NovaDynamicLang\Models\Language
	 */
	protected $model;

	public function __construct(LanguageInterface $language)
	{
		$this->language = $language;
		$this->model = new Model();
	}

	public function exists($lang)
	{
		return $this->language->exists($lang) && $this->model->where('code', $lang)->count() > 0;
	}

	/**
	 * @param $code
	 *
	 * @return mixed
	 */
	public function delete($code)
	{
		$this->model->where('code', $code)->firstOrFail()->delete();

		return $this->language->delete($code);
	}

	public function all()
	{
		return $this->model->all()->map(function ($model) {
			return array_merge([
				'label' => $model->name,
			], $model->toArray());
		});
	}

	public function update(Request $request, $code)
	{
		$language = $this->model->where('code', $code)->firstOrFail();
		$fields = json_decode($request->get('fields', '[]'), true);

		$language->update([
			'short_name' => $request->get('short_name'),
			'fields' => $fields,
			'status' => (bool)$request->get('status'),
		]);

		if ($request->hasFile('flag')) {

			$language->flag->delete();

			$this->uploadFileToFlag($request->file('flag'), $language->id);
		}

		return $this->language->update($code, $fields);
	}

	public function create(Request $request)
	{
		$code = $request->get('code');
		$fields = json_decode($request->get('fields', '[]'), true);

		$this->model->fill([
			'code' => $code,
			'name' => $request->get('label'),
			'short_name' => $request->get('short_name'),
			'fields' => $fields,
			'status' => (bool)$request->get('status'),
		]);

		$this->model->saveOrFail();

		if ($request->hasFile('flag')){
			$this->uploadFileToFlag($request->file('flag'), $this->model->id);
		}

		return $this->language->create($code, $fields);
	}

	public function firstByCode($code)
	{
		$language = $this->model->where('code', $code)->firstOrFail()->toArray();

		$language['fields'] = array_get($this->language->firstByCode($code), 'fields');

		return $language;
	}

	protected function uploadFileToFlag(UploadedFile $file, $id)
	{
		$fileName = time().'_flag'.'.'.$file->getClientOriginalExtension();

		$imageDetail = getimagesize($file->getRealPath());

		LanguageFlag::create([
			'language_id' => $id,
			'file_name' => $fileName,
			'original_name' => $file->getClientOriginalName(),
			'width' => $imageDetail[0],
			'height' => $imageDetail[1],
			'size' => $file->getSize(),
		]);

		return Storage::disk(config('nova-dynamic-lang.disk', 'public'))->putFileAs('flags/', $file, $fileName);
	}

	public function __call($name, $arguments)
	{
		$args = count($arguments) === 1 ? $arguments[0] : $arguments;

		return $this->language->{$name}($args);
	}
}
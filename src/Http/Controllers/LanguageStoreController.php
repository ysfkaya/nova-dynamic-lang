<?php /** @noinspection PhpComposerExtensionStubsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguageStoreController extends Controller
{
	use ValidatesRequests;

	/**
	 * Create a new resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function handle(Request $request)
	{
		$this->validate($request, [
			'language' => [
				'required',
				function ($attribute, $value, $fail) use ($request) {
					if (Language::exists($request->get('code'))) {
						$fail(trans('validation.unique', ['attribute' => 'language']));
					}
				},
			],
			'flag' => 'required|image|dimensions:max_width=32,max_height=32',
			'short_name' => 'required|max:10',
		]);

		Language::create($request);

		return response()->json([
			'success' => true,
			'code' => 200,
		]);
	}
}
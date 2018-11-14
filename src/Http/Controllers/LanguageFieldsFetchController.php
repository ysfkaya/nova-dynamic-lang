<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ysfkaya\NovaDynamicLang\Facades\DynamicMultilingualStore;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguageFieldsFetchController extends Controller
{
	/**
	 * Handle the request.
	 *
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function handle(Request $request)
	{
		$store = DynamicMultilingualStore::driver();

		if ($request->get('mode') === 'edit') {
			$language = Language::firstByCode($request->get('id'));
			$fields = $language['fields'] ?? [];

			$store->withFields($fields);
		}

		return response()->json($store->all());
	}
}
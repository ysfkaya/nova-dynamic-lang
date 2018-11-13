<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguageUpdateController extends Controller
{
	/**
	 * Create a new resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @param $code
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function handle(Request $request, $code)
	{
		$fields = json_decode($request->get('fields', '[]'), true);

		Language::update($code, $fields);

		return response()->json([
			'success' => true,
			'code' => 200,
		]);
	}
}
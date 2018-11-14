<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguageUpdateController extends Controller
{
	use ValidatesRequests;

	/**
	 * Create a new resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @param $code
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function handle(Request $request, $code)
	{
		$this->validate($request, [
			'flag' => 'image|dimensions:max_width=32,max_height=32',
			'short_name' => 'required|max:10',
		]);

		Language::update($request,$code);

		return response()->json([
			'success' => true,
			'code' => 200,
		]);
	}
}
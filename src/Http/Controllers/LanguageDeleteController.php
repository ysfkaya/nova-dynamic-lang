<?php

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Routing\Controller;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguageDeleteController extends Controller
{
	/**
	 * Create a new resource.
	 *
	 * @param $code
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function handle($code)
	{
		Language::delete($code);

		return response()->json([
			'success' => true,
			'code' => 200,
			'languages' => Language::all(),
		]);
	}
}
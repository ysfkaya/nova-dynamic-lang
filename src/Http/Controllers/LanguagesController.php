<?php /** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpComposerExtensionStubsInspection */

namespace Ysfkaya\NovaDynamicLang\Http\Controllers;

use Illuminate\Http\Request;
use Ysfkaya\NovaDynamicLang\Facades\Language;

class LanguagesController
{
	public function index()
	{
		$languages = Language::all();

		return response()->json([
			'languages' => $languages,
		]);
	}

	public function fetch($code)
	{
		return response()->json(Language::firstByCode($code));
	}

	public function defaults(Request $request)
	{
		$contents = Language::getDefaultLanguages();

		return response()->json($contents);
	}
}
<?php

namespace Ysfkaya\NovaDynamicLang\Http\Middleware;

use Ysfkaya\NovaDynamicLang\NovaDynamicLang;

class Authorize
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function handle($request, $next)
	{
		return resolve(NovaDynamicLang::class)->authorize($request) ? $next($request) : abort(403);
	}
}

<?php

namespace App\Http\Middleware;

use App\Http\Responses\V1\CustomResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocal
{

    public function handle(Request $request, Closure $next): Response
    {

        $availableLanguages = config("available_locales.lang");

        $language = $request->header("accept-language", "ar");

        App::setLocale($language);

        if (!in_array($language, $availableLanguages) && !is_null($language)) {
            return CustomResponse::Failure(Response::HTTP_NOT_FOUND, __('locale.Language not found'), [], []);
        }

        return $next($request);
    }
}

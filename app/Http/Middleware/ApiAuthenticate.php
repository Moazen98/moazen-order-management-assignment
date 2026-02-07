<?php

namespace App\Http\Middleware;

use App\Http\Responses\V1\CustomResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticate
{
    public function handle($request, \Closure $next)
    {
        $user = getUserLoginDetails();

        if (!$user) {
            return CustomResponse::Failure(
                Response::HTTP_UNAUTHORIZED,
                __('locale.The Account Not Found'),
                [],
                []
            );
        }

        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}

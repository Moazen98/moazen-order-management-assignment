<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\MainApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\UserRegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Responses\V1\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends MainApiController
{

    public function register(UserRegisterRequest $request)
    {
        $userResponse = app('servicesV1')->authService->register($request->validated());

        if (!$userResponse){
            return CustomResponse::Failure(Response::HTTP_INTERNAL_SERVER_ERROR, __('locale.Data creation failed'), $data = [], []);
        }

        $user = (new UserResource($userResponse))->toArray($request);

        return CustomResponse::Success(Response::HTTP_OK, __('locale.Data registered successfully'), $user, []);
    }

    public function login(LoginRequest $request)
    {
        $loginResponse = app('servicesV1')->authService->login($request->validated());

        if (!$loginResponse['status']){
            return CustomResponse::Failure($loginResponse['response_code'], $loginResponse['message'], $loginResponse['data'], []);
        }

        return CustomResponse::Success($loginResponse['response_code'],$loginResponse['message'],  $loginResponse['data'], []);
    }

    public function me()
    {
        $meResponse =  app('servicesV1')->authService->me();

        if (!$meResponse['status']){
            return CustomResponse::Failure($meResponse['response_code'], $meResponse['message'], $meResponse['data'], []);
        }

        $user = (new UserResource( $meResponse['data']))->toArray();

        return CustomResponse::Success($meResponse['response_code'], $meResponse['message'], $user, []);
    }

    public function logout()
    {
        $logoutResponse =  app('servicesV1')->authService->logout();

        if (!$logoutResponse['status']){
            return CustomResponse::Failure($logoutResponse['response_code'], $logoutResponse['message'], $logoutResponse['data'], []);
        }

        return CustomResponse::Success($logoutResponse['response_code'],$logoutResponse['message'],  $logoutResponse['data'], []);
    }

}

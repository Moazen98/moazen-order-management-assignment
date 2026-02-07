<?php

namespace App\Services;

use App\Repositories\Auth\UserRepositoryInterface;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthService.
 */
class AuthService extends MainService
{

    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function register(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return ['status' => false, 'message' => __('locale.Incorrect username or password'), 'response_code' => Response::HTTP_NOT_FOUND, 'data' => null];

        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return ['status' => false, 'message' => __('locale.Token was not founded'), 'response_code' => Response::HTTP_NOT_FOUND, 'data' => null];
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => __('locale.The token has been blacklisted'), 'response_code' => Response::HTTP_FORBIDDEN, 'data' => null];
        }

        return ['status' => true, 'message' => __('locale.Data uploaded successfully'), 'response_code' => Response::HTTP_OK, 'data' => $user];

    }

    public function logout()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return ['status' => false, 'message' => __('locale.Token was not founded'), 'response_code' => Response::HTTP_NOT_FOUND, 'data' => null];
        }
        JWTAuth::invalidate(JWTAuth::getToken());

        return ['status' => true, 'message' => __('locale.Logged out successfully'), 'response_code' => Response::HTTP_OK, 'data' => null];
    }

    protected function respondWithToken(string $token)
    {
        return [
            'status' => true,
            'message' => __('locale.The login process was successful'),
            'response_code' => Response::HTTP_OK,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]
        ];
    }

}

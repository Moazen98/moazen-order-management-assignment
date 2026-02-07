<?php


use Tymon\JWTAuth\Facades\JWTAuth;

function getUserLoginDetails()
{
    try {
        return JWTAuth::parseToken()->authenticate();
    } catch (\Exception $exception) {
        return null;
    }

}

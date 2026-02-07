<?php

namespace App\Services;

/**
 * Class MainService.
 */
class MainService
{
    protected $paginate = 15;

    public function __construct()
    {
        $paginate = config('custom_settings.paginate') ?? $this->paginate;
    }


}

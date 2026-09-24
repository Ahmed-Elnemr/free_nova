<?php

namespace App\Http\Controllers\Api\Client;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\HomeContentService;

class HomeController extends Controller
{
    public function __construct(
        private HomeContentService $homeContentService,
    ) {}

    public function index()
    {
        return ApiResponse::respondWithArray(
            $this->homeContentService->homepage()
        )->send();
    }
}

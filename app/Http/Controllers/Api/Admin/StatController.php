<?php

namespace App\Http\Controllers\Api\Admin;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Http\JsonResponse;

class StatController extends Controller
{
    public function __construct(
        private readonly UserRepositoryContract $userRepository,
    ) {}

    /**
     * GET /admin/statistics
     *
     * Returns basic dashboard statistics for the admin panel.
     */
    public function index(): JsonResponse
    {
        return ApiResponse::respondWithArray([
            'total_users_count' => $this->userRepository->count(),
        ])->send();
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Class DashboardController
 * @package App\Http\Controllers\API
 */
class DiscountController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getDiscount(): JsonResponse
    {
        return new JsonResponse('discount');
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetDiscountRequest;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;

/**
 * Class DiscountController
 * @package App\Http\Controllers\API
 */
class DiscountController extends Controller
{
    private DiscountService $discountService;

    /**
     * @param DiscountService $discountService
     */
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @param GetDiscountRequest $request
     * @return JsonResponse
     */
    public function getDiscount(GetDiscountRequest $request): JsonResponse
    {
        return new JsonResponse($this->discountService->getDiscount($request->getOrder()));
    }
}

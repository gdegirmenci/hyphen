<?php

return [
    'total_price_based' => [
        'threshold' => env('TOTAL_PRICE_BASED_DISCOUNT_STRATEGY_THRESHOLD', 1000),
        'discount' => env('TOTAL_PRICE_BASED_DISCOUNT_STRATEGY_DISCOUNT', 10),
    ],
    'category_based' => [
        'threshold' => env('CATEGORY_BASED_DISCOUNT_STRATEGY_THRESHOLD', 5),
        'category_id' => env('CATEGORY_BASED_DISCOUNT_STRATEGY_CATEGORY_ID', 2),
    ],
    'product_count_based' => [
        'threshold' => env('PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_THRESHOLD', 2),
        'discount' => env('PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_DISCOUNT', 20),
        'category_id' => env('PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_CATEGORY_ID', 1),
    ],
];

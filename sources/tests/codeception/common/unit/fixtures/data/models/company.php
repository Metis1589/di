<?php

return [
    [
        'id'                       => 1,
        'name'                     => 'Company',
        'code'                     => 'COM',
        'payment_frequency'        => 'daily',
        'payment_frequency_amount' => 10,
        'sales_fee'                => 30,
        'is_vat_exclusive'         => 1,
        'daily_limit'              => 10,
        'weekly_limit'             => 20,
        'monthly_limit'            => 30,
        'limit_type'               => 'Soft',
        'client_id'                => 1,
        'min_order_morning_amount' => 12,
        'min_order_evening_amount' => 16,
        'record_type'              => 'Active'
    ],
    [
        'id'                       => 2,
        'name'                     => 'Company 2',
        'code'                     => 'COM2',
        'payment_frequency'        => 'daily',
        'payment_frequency_amount' => 10,
        'sales_fee'                => 30,
        'is_vat_exclusive'         => 1,
        'daily_limit'              => 10,
        'weekly_limit'             => 20,
        'monthly_limit'            => 30,
        'limit_type'               => 'Soft',
        'client_id'                => 2,
        'min_order_morning_amount' => 22,
        'min_order_evening_amount' => 33,
        'record_type'              => 'Inactive'
    ],
];

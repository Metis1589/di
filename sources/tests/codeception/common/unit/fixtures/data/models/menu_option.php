<?php

return [
    [
        'id' => 1,
        'parent_id' => null,
        'menu_item_id' => 1,
        'name_key' => 'Crust',
        'web_price' => '10.99',
        'restaurant_price' => '11.99',
    ],
    [
        'id' => 2,
        'parent_id' => 1,
        'menu_item_id' => 1,
        'name_key' => 'Thin',
        'web_price' => '2.99',
        'restaurant_price' => '3.99',
    ],
    [
        'id' => 3,
        'parent_id' => 1,
        'menu_item_id' => 1,
        'name_key' => 'Hand Made',
        'web_price' => '1.99',
        'restaurant_price' => '1.49',
    ],
    [
        'id' => 4,
        'parent_id' => 2,
        'menu_item_id' => 1,
        'name_key' => 'Thin Thin Thin',
        'web_price' => '1.99',
        'restaurant_price' => '1.49',
    ],
];

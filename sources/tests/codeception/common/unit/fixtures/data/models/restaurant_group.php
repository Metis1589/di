<?php

return [
    [
        'id' => 1,
        'name_key' => 'chain 1 - rest group 1',
        'restaurant_chain_id' => 1,
        'record_type' => 'Active',
    ],
    [
        'id' => 11,
        'name_key' => 'chain 1 - group 1.1',
        'restaurant_chain_id' => 1,
        'parent_id' => 1,
        'record_type' => 'Active',
    ],
    [
        'id' => 111,
        'name_key' => 'chain 1 - group 1.1.1',
        'restaurant_chain_id' => 1,
        'parent_id' => 11,
        'record_type' => 'Active',
    ],
    [
        'id' => 112,
        'name_key' => 'chain 1 - group 1.1.2',
        'restaurant_chain_id' => 1,
        'parent_id' => 11,
        'record_type' => 'Active',
    ],


    [
        'id' => 2,
        'name_key' => 'chain 2 - group 2',
        'restaurant_chain_id' => 2,
        'record_type' => 'Active',
    ],
    [
        'id' => 21,
        'name_key' => 'chain 2 - group 2.1',
        'restaurant_chain_id' => 2,
        'parent_id' => 11,
        'record_type' => 'Active',
    ],
];

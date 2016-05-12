<?php

use common\enums\UserType;


return [
    UserType::UNAUTHORIZED => [
        'type' => 0,
    ],
    UserType::Member => [
        'type' => 1,
    ],
    UserType::CorporateMember => [
        'type' => 2,
    ],
    UserType::Admin => [
        'type' => 3,
    ],
    UserType::RestaurantAdmin => [
        'type' => 4,
    ],
    UserType::RestaurantGroupAdmin => [
        'type' => 5,
    ],
    UserType::RestaurantChainAdmin => [
        'type' => 6,
    ],
    UserType::CorporateAdmin => [
        'type' => 7,
    ],
    UserType::RestaurantTeam => [
        'type' => 8,
    ],
    UserType::Finance => [
        'type' => 9,
    ],
    UserType::ClientAdmin => [
        'type' => 10,
    ],
    UserType::RestaurantApp => [
        'type' => 11,
    ],
];

<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   5/20/15
 * @time   6:04 PM
 */
use common\enums\DeliveryType;

return [
    [
        'food_preparation_time'     => '00:30:00',
        'restaurant_id'             => 33,
        'delivery_type'             => DeliveryType::CollectionAsap,
        'subtotal'                  => 10,
        'discount_value'            => 0,
        'total'                     => 100,
        'postcode'                  => 'N1 7EH',
        'delivery_address_data'     => serialize(
            [
                'country_id'   => 1,
                'instructions' => '',
                'email'        => 'test@mail.com',
                'address1'     => 'Test address',
                'address2'     => 'Test address',
                'address3'     => 'Test address',
                'city'         => 'city',
                'postcode'     => 'KT1 3EG',
                'name'         => 'Test name',
                'phone1'       => '000000000000',
                'longitude'    => -0.292332,
                'latitude'     => 51.4086,
            ]
        ),
        'currency_code'             => 'UA',
        'delivery_charge'           => 10,
        'restaurant_subtotal'       => 0,
        'restaurant_discount_value' => 0,
        'restaurant_total'          => 50,
        'estimated_time'            => '00:10',
        'record_type'               => 'Active'
    ]
];
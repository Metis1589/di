<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherCategory extends BaseEnum {
    const Free = 'Free';
    const Delivery = 'Delivery';
    const Wine = 'Wine';
    const Food = 'Food';
    const All = 'All';
    const MenuItems = 'Menu Items';
//    const EatFoodLate = 'Eat food late';
    const FoodPrice = 'Food Price';
    const FreeWithinCategory = 'FreeWithinCategory';
    const FreeItem = 'FreeItem';
    const OffByCategory = 'OffByCategory';
    const MultipleItemsSinglePrice = 'MultipleItemsSinglePrice';
    const MultipleCategoriesSinglePrice = 'MultipleCategoriesSinglePrice';

    public static function getLabels() {
        return [
            self::Free => T::l('Free'),
            self::Delivery => T::l('Delivery'),
            self::Wine => T::l('Wine'),
            self::Food => T::l('Food'),
            self::All => T::l('All'),
            self::MenuItems => T::l('Menu Items'),
//            self::EatFoodLate => T::l('Eat Food Late'),
            self::FoodPrice => T::l('Food Price'),

            self::FreeWithinCategory => T::l('Free Within Category'),
            self::FreeItem => T::l('Free Item'),
            self::OffByCategory => T::l('Off By Category'),
            self::MultipleItemsSinglePrice => T::l('Multiple Items Single Price'),
            self::MultipleCategoriesSinglePrice => T::l('Multiple Categories Single Price'),
        ];
    }
}


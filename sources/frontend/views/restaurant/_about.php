<?php
/* @var $this yii\web\View */
use frontend\components\language\T;
?>

<h3 class="form_devider only_desctop"><?= $model['name'] ?></h3>
<div class="content_set_inner">
    <div class="content_set_left">
        <h4><?= T::l('ABOUT') ?></h4>
        <?= $model['description'] ?>
    </div>
    <div class="content_set_right">
        <h4><?= T::l('ADDRESS') ?></h4>
        <table>
            <tr>
                <td>
                    <?= $model['physicalAddress']['address1'] ?>
                    <?= $model['physicalAddress']['city'] ?>,
                    <?= $model['physicalAddress']['postcode'] ?>
                </td>
                <td></td>
            </tr>
        </table>
        <?php if (array_key_exists('delivery', $model['restaurantSchedules'])): ?>
            <h4><?= T::l('Delivery Hours') ?></h4>
            <table>
                <?php foreach ($model['restaurantSchedules']['delivery'] as $day => $schedules): ?>
                    <tr>
                        <td>
                            <?= T::l($day) ?>
                        </td>
                        <td>
                            <?php foreach ($schedules as $schedule): ?>
                                <?= date('H:i', strtotime($schedule['from'])) ?> - <?= date('H:i', strtotime($schedule['to'])) ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (array_key_exists('opening', $model['restaurantSchedules'])): ?>
            <h4><?= T::l('Opening Hours') ?></h4>
            <table>
                <?php foreach ($model['restaurantSchedules']['opening'] as $day => $schedules): ?>
                    <tr>
                        <td>
                            <?= T::l($day) ?>
                        </td>
                        <td>
                            <?php foreach ($schedules as $schedule): ?>
                                <?= date('H:i', strtotime($schedule['from'])) ?> - <?= date('H:i', strtotime($schedule['to'])) ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <table class="atributes">
            <?php if ($model['restaurantProperties']): ?>
                <?php if (array_key_exists('min_delivery_order_value', $model['restaurantProperties'])): ?>
                    <tr>
                        <td><?= T::l('Delivery Order') ?></td>
                        <td><?= $model['currency']['symbol'] . number_format($model['restaurantProperties']['min_delivery_order_value'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (array_key_exists('min_collection_order_value', $model['restaurantProperties'])): ?>
                    <tr>
                        <td><?= T::l('Collection Order') ?></td>
                        <td><?= $model['currency']['symbol'] . number_format($model['restaurantProperties']['min_collection_order_value'], 2) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <tr>
                <td><?= T::l('Collection Charge') ?></td>
                <td><?= T::l('Free') ?></td>
            </tr>
        </table>
    </div>
</div>


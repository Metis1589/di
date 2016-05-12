<?php
use frontend\components\language\T;

?>
<div ng-show="tab == 'loyalityPoints'">
    <h3 class="only_mobile form_devider"><?=T::l('MY DINEIN POINTS')?></h3>
    <div class="point_balance_wrapp form_devider">
        <div class="point_balance">
            <h5><?=T::l("LOYALTY PAYS! YOU'VE EARNED")?></h5>
            <span class="point_balance">{{profile.loyalty_points}}</span>
            <h5><?=T::l("DINEIN LOYALTY POINTS")?></h5>
<!--            <p>--><?//=T::l('This converts')?><!-- <span>to £XX.XX</span> --><?//=T::l('in free meals! Copy that talks about the ways you can earn more points. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed velit dapibus, vestibulum augue non.')?><!--</p>-->
        </div>
    </div>
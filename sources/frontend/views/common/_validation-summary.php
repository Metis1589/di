<?php

use common\components\language\T;

?>

<div ng-show="isFormInvalid()" class="invalid_details">
    <ul>
        <li ng-show="customError"><span>{{customError}}</span></li>
        <li ng-repeat="e in errors"><span>{{ e }}</span></li>
    </ul>
</div>
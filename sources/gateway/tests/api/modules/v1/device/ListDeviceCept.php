<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all devices');
$I->sendGET('devices');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

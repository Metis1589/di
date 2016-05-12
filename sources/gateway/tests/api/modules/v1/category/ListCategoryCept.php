<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all categories');
$I->sendGET('categories');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

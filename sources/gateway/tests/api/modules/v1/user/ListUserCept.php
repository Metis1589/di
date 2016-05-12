<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all users');
$I->sendGET('users');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

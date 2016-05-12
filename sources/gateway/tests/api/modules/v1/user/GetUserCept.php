<?php

$I = new ApiGuy($scenario);
$I->wantTo('get user by id');
$I->sendGET('users/1');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

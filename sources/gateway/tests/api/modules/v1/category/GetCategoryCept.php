<?php

$I = new ApiGuy($scenario);
$I->wantTo('get category by id');
$I->sendGET('categories/495');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

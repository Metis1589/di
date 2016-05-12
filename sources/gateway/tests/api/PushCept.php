<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all bids');
$I->sendGET('pushes/android');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

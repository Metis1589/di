<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all bids');
$I->sendGET('bids');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

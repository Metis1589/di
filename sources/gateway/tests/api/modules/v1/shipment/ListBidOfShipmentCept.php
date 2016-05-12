<?php

$I = new ApiGuy($scenario);
$I->wantTo('get all bids of a shipment');
$I->sendGET('shipments/1/bids');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

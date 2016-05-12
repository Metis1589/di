<?php
/**
 * Copyright (c) 2014 Ebizu Sdn. Bhd.
 */

namespace gateway\tests\unit\modules\v1\repositories;

use gateway\tests\unit\DbTestCase;
use gateway\modules\v1\repositories\shipment\ArShipmentRepository;


class ArShipmentRepositoryTest extends DbTestCase
{
    protected $shipmentRepo;
    
    public function fixtures()
    {
        return [
            //'users' => UserFixture::className()
        ];
    }
    
    protected function _before()
    {
        $this->shipmentRepo = new ArShipmentRepository();
    }
    
    public function testFindActiveBids()
    {
        // arrange
        $shipmentId = 5;
        
        // act
        $activeBids = $this->shipmentRepo->findActiveBidsByShipment($shipmentId);
        
        // assert
        $this->assertNotEmpty($activeBids);
        $this->assertEquals(count($activeBids), 1);
        
    }
}

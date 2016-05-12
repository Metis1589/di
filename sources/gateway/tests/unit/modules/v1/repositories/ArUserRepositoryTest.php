<?php
/**
 * Copyright (c) 2014 Ebizu Sdn. Bhd.
 */

namespace gateway\tests\unit\modules\v1\repositories;

use gateway\tests\unit\DbTestCase;
use gateway\modules\v1\repositories\user\ArUserRepository;
use gateway\tests\fixtures\modules\v1\repositories\UserFixture;

class ArUserRepositoryTest extends DbTestCase
{
    protected $userRepo;
    
    public function fixtures()
    {
        return [
            'users' => UserFixture::className()
        ];
    }
    
    protected function _before()
    {
        $this->userRepo = new ArUserRepository();
    }
    
    public function testFindByEmail()
    {
        // arrange
        $email = 'halimi@gmail.com';        
        
        // act
        $user = $this->userRepo->findByEmail($email);
        
        // assert
        $this->assertEquals($user->usr_email, 'halimi@gmail.com');
        $this->assertEquals($user->usr_username, 'halimi');
    }
}

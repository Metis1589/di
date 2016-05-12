<?php
/**
 * Copyright (c) 2014 Ebizu Sdn. Bhd.
 */

namespace gateway\tests\unit\modules\v1\services;

use \Mockery as m;
use gateway\tests\unit\TestCase;
use gateway\modules\v1\services\UserService;
use gateway\modules\v1\repositories\user\ArUserRepository;

class UserServiceTest extends TestCase
{
    protected $userService;
    protected $mockUserRepo;
    
    public function _before()
    {
        $this->userService = new UserService(new ArUserRepository);
        $this->mockUserRepo = m::mock('gateway\modules\v1\repositories\user\ArUserRepository');
    }
    
    public function testLogin()
    {
        // arrange
        $returnUser = new \stdClass();
        $returnUser->usr_username = 'halimi';
        $returnUser->usr_password = '$2a$08$pQUDHbfS9gwGjAJq0IETz.ATDmZJH7CbXhHn0fzb0zhZyWQ8MlGBy';
                
        $this->mockUserRepo->shouldReceive('findByEmail')->times(1)->andReturn($returnUser);
        
        $email = 'halimi@gmail.com';
        $password = 'halimi';

        // act
        $result = $this->userService->login($email, $password);
        
        // assert
        $this->assertArrayNotHasKey('status', $result);        
    }
    
    public function testLoginWrongEmail()
    {
        // arrange                        
        $this->mockUserRepo->shouldReceive('findByEmail')->times(1)->andReturn(null);
        
        $email = 'fake@gmail.com';
        $password = 'fake';

        // act
        $result = $this->userService->login($email, $password);
        
        // assert        
        $this->assertEquals($result['status'], 'error');        
        $this->assertEquals($result['code'], 404);        
    }
}

<?php
/**
 * Copyright (c) 2014 Ebizu Sdn. Bhd.
 */

namespace gateway\tests\unit\modules\v1\repositories;

use gateway\tests\unit\DbTestCase;
use gateway\modules\v1\repositories\category\ArCategoryRepository;


class ArCategoryRepositoryTest extends DbTestCase
{
    protected $categoryRepo;
    
    public function fixtures()
    {
        return [
            //'users' => UserFixture::className()
        ];
    }
    
    protected function _before()
    {
        $this->categoryRepo = new ArCategoryRepository();
    }
}

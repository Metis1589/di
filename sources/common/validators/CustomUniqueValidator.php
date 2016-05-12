<?php
namespace common\validators;

use yii\validators\UniqueValidator;
use common\enums\RecordType;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomUniqueValidator
 *
 * @author Yura
 */
class CustomUniqueValidator extends UniqueValidator {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->filter =  "record_type <> '".RecordType::Deleted."'";
    }
}

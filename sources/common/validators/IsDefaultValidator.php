<?php

namespace common\validators;
use Yii;
use yii\validators\Validator;

/**
 * Validator for is_default item 
 *
*/
class IsDefaultValidator extends Validator
{
  
    public function validateAttribute($object, $attribute)
    {
        if ($object->is_default && ($object->record_type == \common\enums\RecordType::Deleted) )
        {
            $this->adderror($object, $attribute,  Yii::t('error', 'Default item can not be deleted'));
        }
    }
}
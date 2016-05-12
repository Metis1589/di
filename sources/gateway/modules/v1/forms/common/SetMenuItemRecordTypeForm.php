<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use common\enums\RecordType;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class SetMenuItemRecordTypeForm extends BaseRequestApiForm
{
    public $menu_item_id;
    public $record_type;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['menu_item_id', 'required', 'message' => T::e('Menu Item ID is missing')],

            ['record_type', 'required', 'message' => Yii::t('error', 'Missing record type.')],
            ['record_type', 'validateRecordType'],
        ];
    }

    public function validateRecordType($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->record_type != RecordType::Active && $this->record_type != RecordType::InActive) {
                $this->addError($attribute, Yii::t('error','Invalid record type'));
            }
        }
    }
}
<?php

namespace gateway\modules\v1\forms;
use Yii;

class PaymentRequestApiForm extends FormModel {

    public function rules() {
        return array_merge(
                [
                ], $this->customRules()
        );
    }

    /**
     * Custom validation rules. Can be re-declared in the child class to extend validation rules.
     *
     * @return array
     */
    protected function customRules() {
        return [
        ];
    }
}

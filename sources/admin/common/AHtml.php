<?php

namespace admin\common;


use common\components\language\T;
use yii\helpers\Html;

class AHtml {

    static $supportedAngularValidators = [
        'required' => 'required',
        'number' => 'number',
        'pattern' => 'pattern',
        'email' => 'email',
        'min' => 'min',
        'max' => 'max',
        'equals' => 'equals'
    ];

    public static function input($label, $inputOptions, $validationSettings = [], $wrapperOptions = [], $labelOptions = [])
    {
        $appendErrorMessage = $label;
        if (!empty($label)) {
            $for = isset($inputOptions['id']) ? $inputOptions['id'] : '';
            if (!isset($labelOptions['class'])) {
                $labelOptions['class'] = 'control-label';
            }
            $label = Html::label(static::translateIfNotAngular($label), $for, $labelOptions);
        } else {
            $label = '';
        }

        $id =  isset($inputOptions['id']) ? $inputOptions['id'] : '';
        $name =  isset($inputOptions['name']) ? $inputOptions['name'] : $id;
        $value =  isset($inputOptions['value']) ? $inputOptions['value'] : null;
        $type =  isset($inputOptions['type']) ? $inputOptions['type'] : 'text';
        if (!isset($inputOptions['class']) && in_array($type,['text','number','email','password','select'])) {
            $inputOptions['class'] = 'form-control';
        }

        if ($type == 'email') {
            $inputOptions['pattern'] = '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{1,4}$';
        }

        $template = '{label}{input}{errors}';
        if (isset($inputOptions['template'])) {
            $template = $inputOptions['template'];
            unset($inputOptions['template']);
        }

        if (in_array($type, ['radiolist','select'])) {
            $selection = null;
            if (isset($inputOptions['selection'])) {
                $selection = $inputOptions['selection'];
            }
            $options = null;
            if (isset($inputOptions['options'])) {
                $options = $inputOptions['options'];
            }
            $items = $inputOptions['items'];
            if ($type == 'radiolist') {
                $input = Html::radioList($name, $selection, $items, $options);
            } else if ($type == 'select') {
                unset($inputOptions['type']);
                unset($inputOptions['items']);
                $input = Html::dropDownList($name, $selection, $items, $inputOptions);
            }

        }
        elseif($type=='editor'){
            $value = \yii\helpers\Html::decode($value);
            $input = \mihaildev\ckeditor\CKEditor::widget([
                'name' => $name,
                'value' => $value,
                'editorOptions' => ['allowedContent' => true] + \mihaildev\elfinder\ElFinder::ckeditorOptions('manager', []),
                'options' => $inputOptions
            ]);
        }
        else {
            $input = Html::input($type, $name, $value, $inputOptions);
        }

        $validators = static::inputErrors($name, $validationSettings, $appendErrorMessage);

        if (!isset($wrapperOptions['class'])) {
            $wrapperOptions['class'] = 'form-group';
        }

        $content = str_replace('{label}',$label, $template);
        $content = str_replace('{input}',$input, $content);
        $content = str_replace('{errors}',$validators, $content);

        if ($type == 'checkbox') {
            $content = $input . $label .$validators;
        }
        return Html::tag('div', $content, $wrapperOptions);
    }

    public static function inputErrors($inputName, $validationSettings, $appendMessage = '')
    {
        $validators = '';
        if (!empty($validationSettings)){
            $formName = $validationSettings['form-name'];
            $rules = [];
            if (isset($validationSettings['rules'])) {
                $rules = $validationSettings['rules'];
            }
            foreach($rules as $rule => $message) {
                $show = isset(static::$supportedAngularValidators[$rule]) ? $formName .'.' . $inputName . '.' .'$error.'. $rule : $rule;
                if (StringHelper::startsWith($message, '...')) {
                    $message = str_replace('...', '', $message);
                    $message = $appendMessage.$message;
                }

                $validators .= Html::tag('div', T::e($message), ['class' => 'help-block', 'ng-show' => $show]);
            }
            $validators = Html::tag('div', $validators, ['ng-show' => $formName . '.' . $inputName . '.$dirty && (' .$formName. '.$submitted || ' . $formName . '.' . $inputName . '.$touched)' ]);
        }
        return $validators;
    }

    public static function waitSpinner($options = [])
    {
        $options['class'] = 'wait';
        return Html::tag('div', Html::tag('div',Html::tag('h1',Html::tag('i','',['class'=>'fa fa-spinner fa-spin green fa-2x'])),['class' => 'wait-content']), $options);
    }

    public static function errorNotification($message,$options = [])
    {
        $options['class'] = 'alert alert-danger alert-dismissable';
        return Html::tag('div',$message,$options);
    }

    public static function saveButton($options, $text = 'Save') {
        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-primary col-xs-12';
        }
        return Html::tag('div',Html::tag('div',Html::tag('div', Html::submitButton(static::translateIfNotAngular($text), $options),['class' => 'form-group']),['class' => 'col-xs-12']),['class' => 'row']);
    }


    private static function translateIfNotAngular($str) {
        if (StringHelper::hasSubString($str,'{{')) {
            return $str;
        }
        return T::l($str);
    }

} 
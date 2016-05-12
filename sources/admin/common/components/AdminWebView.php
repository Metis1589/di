<?php
namespace admin\common\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class AdminWebView extends View {

    public $action_buttons;

    public function registerActionButton($link, $title, array $options = null)
    {
        if (!isset($this->action_buttons))
        {
            $this->action_buttons = array();
        }
        $this->action_buttons[] = [
            'link' => Url::to($link),
            'title' => Yii::t('label', $title),
            'options' => $options
        ];
    }

    public function renderActionButtons()
    {
        $result = '';
        if (isset($this->action_buttons)) {
            foreach($this->action_buttons as $button) {
                $attributes = 'class="btn btn-primary"';
                if (isset($button['options'])) {
                    $attributes = Html::renderTagAttributes($button['options']);
                }
                $result .= '<a href="'.$button['link'].'" '.$attributes.'>'.$button['title'].'</a>' ;
            }
        }
        return $result;
    }

    public function registerModelActionButtons($model)
    {
        if (!$model->isNewRecord) {
            $this->registerActionButton([$model->record_type == 'Active' ? 'deactivate' : 'activate', 'id' => $model->id], $model->record_type == 'Active' ? 'Deactivate' : 'Activate', [
                'class' => $model->record_type == 'Active' ? 'btn btn-danger ' : 'btn btn-success',
            ]);

            if (!isset($model['is_default']) ||  (isset($model['is_default']) && !$model->is_default)) {
                $this->registerActionButton(['delete', 'id' => $model->id], 'Delete', [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('label', 'Are you sure you want to delete?'),
                        'method' => 'post',
                    ],
                ]);
            }
        }
    }

    public function action($url, $params = null)
    {
        return Yii::$app->controller->run($url, $params);
    }
}
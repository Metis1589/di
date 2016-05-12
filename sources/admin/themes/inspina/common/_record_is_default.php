<div class="row">
    <div class="col-xs-10">
        <?= $form->field($model, 'is_default')->checkbox(['disabled' => $model->is_default ? true : false, 'class' => 'i-checks']) ?>
    </div>
</div>
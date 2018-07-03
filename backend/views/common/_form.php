<?php

/* @var $formView yii\web\View */
/* @var $model \yii\db\ActiveRecord */
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body" style="padding: 15px;">
            <?= $this->render('@app/views/'.$formView, [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
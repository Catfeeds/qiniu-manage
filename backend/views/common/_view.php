<?php

use yii\widgets\DetailView;
use common\components\Formatter;

/* @var $model common\models\PermissionCategory */
/* @var $columns array */

?>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body" style="padding: 15px;">
            <?=
                DetailView::widget([
                    'model' => $model,
                    'formatter' => new Formatter(),
                    'options' => [
                        'class' => 'layui-table'
                    ],
                    'attributes' => $columns,
                ])
            ?>
        </div>
    </div>
</div>

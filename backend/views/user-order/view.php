<?php

use yii\widgets\DetailView;
use common\components\Formatter;

/* @var $model common\models\UserOrder */
/* @var $columns array */
/* @var $holderColumns array */
/* @var $insuredColumns array */

?>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">订单基础信息</div>
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

    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">投保人信息</div>
                <div class="layui-card-body" style="padding: 15px;">
                    <?=
                    DetailView::widget([
                        'model' => $model->holder,
                        'formatter' => new Formatter(),
                        'options' => [
                            'class' => 'layui-table'
                        ],
                        'attributes' => $holderColumns,
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">被保人人信息</div>
                <div class="layui-card-body" style="padding: 15px;">
                    <?=
                    DetailView::widget([
                        'model' => $model->insured,
                        'formatter' => new Formatter(),
                        'options' => [
                            'class' => 'layui-table'
                        ],
                        'attributes' => $insuredColumns,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

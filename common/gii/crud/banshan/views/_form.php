<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator common\gii\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
?>

<form class="layui-form" lay-filter="layui-form" method="post">
<?php foreach ($generator->getColumnNames() as $attribute): ?>
<?php if($model->isAttributeRequired($attribute)): ?>

    <div class="layui-form-item">
        <label class="layui-form-label"><?=$model->attributeLabels()[$attribute]?></label>
        <div class="layui-input-block">
            <textarea name="<?=$attribute?>" placeholder="请输入<?=$model->attributeLabels()[$attribute]?>" class="layui-textarea"></textarea>
        </div>
    </div>
<?php endif; ?>
<?php endforeach; ?>

    <div class="layui-form-item layui-layout-admin">
        <div class="layui-input-block">
            <div class="layui-footer" style="left: 0;">
                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('新建') ?> : <?= $generator->generateString('更新') ?>, ['class' => $model->isNewRecord ? 'btn btn-success layui-btn' : 'btn btn-primary layui-btn']) ?>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </div>
</form>

<?='<?php $this->beginBlock(\'js_footer\') ?>'?>
<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form'], function() {
        var $ = layui.$
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form;
        form.val("layui-form", <?='<?= json_encode($model->attributes) ?>'?>);
        form.render(null, 'layui-form');
    });
</script>
<?='<?php $this->endBlock(); ?>'?>
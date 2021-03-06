<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthAccount */
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">别名</label>
        <div class="layui-input-block">
            <input type="text" name="alias" lay-verify="required" placeholder="请输入别名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <?php if(!$model->id): ?>
    <div class="layui-form-item">
        <label class="layui-form-label">Access Key</label>
        <div class="layui-input-block">
            <input type="text" name="accessKey" lay-verify="required" placeholder="请输入Access Key" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Secret Key</label>
        <div class="layui-input-block">
            <input type="text" name="secretKey" lay-verify="required" placeholder="请输入Secret Key" autocomplete="off" class="layui-input">
        </div>
    </div>
    <?php endif; ?>

    <div class="layui-form-item layui-layout-admin">
        <div class="layui-input-block">
            <div class="layui-footer" style="left: 0;">
                <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success layui-btn' : 'btn btn-primary layui-btn']) ?>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </div>
</form>

<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form'], function() {
        var $ = layui.$
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form;
        form.val("layui-form", <?= json_encode($model->attributes) ?>);
        form.render(null, 'layui-form');
    });
</script>
<?php $this->endBlock(); ?>
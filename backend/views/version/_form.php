<?php

use yii\helpers\Html;
use common\models\Version;

/* @var $this yii\web\View */
/* @var $model \common\models\Version */
/* @var $form yii\widgets\ActiveForm */
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <input type="hidden" name="adminID">

    <div class="layui-form-item">
        <label class="layui-form-label">版本名称 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" placeholder="请输入版本名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">版本类型 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <select name="categoryID" lay-search="" lay-filter="categoryID" lay-verify="required">
                <?php foreach (Version::$labelTypes as $typeID => $typeName): ?>
                    <option value="<?= $typeID ?>"><?= $typeName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">版本号 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="version" lay-verify="required" placeholder="请输入版本号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">版本说明 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="info" lay-verify="required" placeholder="请输入版本说明" autocomplete="off" class="layui-input">
        </div>
    </div>

    <?= $this->render('@app/views/common/_upload', ['name'=>'url', 'label'=>'下载链接', 'value'=>$model->url, 'btnLabel'=>'上传安裝包', 'isImage'=>false]) ?>

    <div class="layui-form-item layui-layout-admin">
        <div class="layui-input-block">
            <div class="layui-footer" style="left: 0;">
                <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['lay-submit'=>'','lay-filter'=>'category-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success layui-btn' : 'btn btn-primary layui-btn']) ?>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </div>
</form>

<?php $this->beginBlock('js_footer') ?>
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
            form.val("layui-form", <?= json_encode($model->attributes) ?>);
            form.render(null, 'layui-form');
        });
    </script>
<?php $this->endBlock(); ?>

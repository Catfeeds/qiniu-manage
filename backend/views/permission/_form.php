<?php

use yii\helpers\Html;
use backend\models\PermissionCategory;

/* @var $this yii\web\View */
/* @var $model \common\models\Ad */
/* @var $form yii\widgets\ActiveForm */

?>

<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">权限分组 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <select name="categoryID" lay-search="" lay-filter="parent" lay-verify="required">
                <?php foreach (PermissionCategory::allOptions() as $categoryID => $categoryName): ?>
                    <option value="<?= $categoryID ?>"><?= $categoryName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">权限名称 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">控制器 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="controller" lay-verify="required" placeholder="请输入控制器" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">操作 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="action" lay-verify="required" placeholder="请输入操作" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <textarea name="description" placeholder="请输入备注" class="layui-textarea"></textarea>
        </div>
    </div>

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

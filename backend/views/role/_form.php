<?php

use yii\helpers\Html;
use backend\models\Permission;

/* @var $this yii\web\View */
/* @var $model \backend\models\Role */
/* @var $form yii\widgets\ActiveForm */
$permissionIDs = [];
foreach ($model->permissions as $permission){
    $permissionIDs[] = $permission['id'];
}
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">角色名称 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" placeholder="请输入角色名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <textarea name="description" placeholder="请输入备注" class="layui-textarea"></textarea>
        </div>
    </div>

    <?php foreach (Permission::allPermissionOptions() as $permissionCategory): ?>
        <div class="layui-form-item permission-wrap">
            <label class="layui-form-label"><?=$permissionCategory['name']?></label>
            <div class="layui-input-block">
                <?php foreach ($permissionCategory['permissions'] as $permission): ?>
                    <input type="checkbox" name="permissionIDs[]" title="<?=$permission['name']?>" value="<?=$permission['id']?>" <?php if(in_array($permission['id'], $permissionIDs)): ?>checked<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

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

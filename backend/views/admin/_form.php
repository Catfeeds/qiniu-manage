<?php

use yii\helpers\Html;
use backend\models\Role;

/* @var $this yii\web\View */
/* @var $model \backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
$roleIDs = [];
foreach ($model->roles as $role){
    $roleIDs[] = $role['id'];
}
$model->password = '';
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">账号 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="username" lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码<?php if(!$model->id): ?><span class="necessary">*</span><?php endif; ?></label>
        <div class="layui-input-block">
            <input type="password" name="password" <?php if(!$model->id): ?>lay-verify="required"<?php endif; ?> placeholder="请输入密码，修改时为空不修改" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="realName" placeholder="请输入真实姓名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
            <input type="text" name="phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="text" name="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">启用</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" lay-text="ON|OFF" lay-filter="status">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            <?php foreach (Role::allOptions() as $role): ?>
                <input type="checkbox" name="roleIDs[]" title="<?=$role['name']?>" value="<?=$role['id']?>" <?php if(in_array($role['id'], $roleIDs)): ?>checked<?php endif; ?>>
            <?php endforeach; ?>
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

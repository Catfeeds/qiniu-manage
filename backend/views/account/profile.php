<?php

use yii\helpers\Url;
use backend\models\Admin;
use common\components\Formatter;

/* @var $model Admin */
$formatter = new Formatter();
$roles = $formatter->asAdminRoles($model->roles);
?>

<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">我的角色</label>
        <div class="layui-input-block">
            <input type="text" name="roles" value="<?=$roles?>" readonly lay-verify="required" autocomplete="off" placeholder="请输入姓名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="username" readonly class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">用于后台登入名,不可修改</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="realName" lay-verify="required" autocomplete="off" placeholder="请输入姓名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-inline">
            <input type="text" name="phone" lay-verify="phone" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit>确认修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重新填写</button>
        </div>
    </div>
</form>
<?php $this->beginBlock('js_footer') ?>
<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'set'], function() {
        var $ = layui.$
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form;
            form.val("layui-form", <?= json_encode($model->attributes) ?>);
            form.render(null, 'layui-form');
    });
</script>
<?php $this->endBlock(); ?>
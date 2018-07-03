<?php

use yii\helpers\Url;
use backend\models\Admin;

/* @var $model Admin */
?>
<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">当前密码</label>
        <div class="layui-input-inline">
            <input type="password" name="oldPassword" lay-verify="required" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">新密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="pass" autocomplete="off" id="LAY_password" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">6到16个字符</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认新密码</label>
        <div class="layui-input-inline">
            <input type="password" name="repassword" lay-verify="repass" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit>确认修改</button>
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
        });
    </script>
<?php $this->endBlock(); ?>
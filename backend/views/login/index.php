<?php
use yii\helpers\Url;
/* @var $geetest \Jormin\Geetest\Geetest  */
?>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>后台管理系统</h2>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <?= $geetest->view() ?>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
            </div>
        </div>
    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>© <?= date('Y') ?> <a href="http://www.layui.com/" target="_blank">layui.com</a></p>
    </div>
</div>

<?php
$js = <<<JS
    layui.config({
        base: '/layuiadmin/',
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user'], function(){
        var  form = layui.form,
            admin = layui.admin;
        form.on('submit(LAY-user-login-submit)', function(obj){
            var params = obj.field;
            if(!params.geetest_challenge){
                layer.msg('请完成验证码');
            }
            admin.req({
                url: "/login.html",
                type: 'POST',
                data: params,
                done: function(res){
                    layer.msg(res.msg, {anim: 0}, function(){
                        location.href = '/';
                    });
                },
                failed: function(res){
                    layer.closeAll();
                    layer.msg(res.msg, {anim: 0}, function(){
                        initGeetestCaptcha();
                    });
                }
            });
        });
    });
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
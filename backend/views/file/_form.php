<?php

use yii\helpers\Html;
use common\models\AuthAccount;

/* @var $this yii\web\View */
/* @var $model array */
?>
    <form class="layui-form" lay-filter="layui-form" method="post">

        <input type="hidden" name="id">

        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="key" lay-verify="required" placeholder="请输入文件旧名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">MIME</label>
            <div class="layui-input-block">
                <input type="text" name="mimeType" lay-verify="required" placeholder="请输入文件MIME" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">存储类型</label>
            <div class="layui-input-block">
                <select name="type" lay-filter="type" lay-verify="required">
                    <option value="0">标准存储</option>
                    <option value="1">低频存储</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">同名覆盖</label>
            <div class="layui-input-block">
                <input type="checkbox" name="force" lay-skin="switch" lay-text="ON|OFF" lay-filter="force">
            </div>
        </div>

        <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
                <div class="layui-footer" style="left: 0;">
                    <?= Html::submitButton('重命名', ['class' => 'btn btn-success layui-btn']) ?>
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
        form.val("layui-form", <?= json_encode($model) ?>);
        form.render(null, 'layui-form');
    });
</script>
<?php $this->endBlock(); ?>
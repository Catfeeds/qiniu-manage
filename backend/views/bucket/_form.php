<?php

use yii\helpers\Html;
use common\models\AuthAccount;

/* @var $this yii\web\View */
/* @var $model common\models\Bucket */
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">授权账号</label>
        <div class="layui-input-block">
            <select name="accountID" lay-search="" lay-filter="accountID" lay-verify="required">
                <?php foreach (AuthAccount::options() as $accountID => $accessKey): ?>
                    <option value="<?= $accountID ?>"><?= $accessKey ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">空间名称</label>
        <div class="layui-input-block">
            <input type="text" name="bucket" lay-verify="required" placeholder="请输入空间名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">空间绑定域名</label>
        <div class="layui-input-block">
            <textarea name="domains" placeholder="请输入空间绑定域名" class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">默认域名</label>
        <div class="layui-input-block">
            <input type="text" name="defaultDomain" lay-verify="required" placeholder="请输入默认域名" autocomplete="off" class="layui-input">
        </div>
    </div>

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
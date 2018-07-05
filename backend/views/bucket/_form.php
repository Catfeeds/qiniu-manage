<?php

use yii\helpers\Html;
use common\models\AuthAccount;

/* @var $this yii\web\View */
/* @var $model common\models\Bucket */
$domains = $model->domains ? json_decode($model->domains) : [];
$domains && $model->domains = implode(' | ', $domains);
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">授权账号</label>
        <div class="layui-input-block">
            <select name="accountID" lay-search="" lay-filter="accountID" lay-verify="required" <?=$model->id ? 'disabled' : ''?>>
                <?php foreach (AuthAccount::options() as $accountID => $alias): ?>
                    <option value="<?= $accountID ?>"><?= $alias ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">空间名称</label>
        <div class="layui-input-block">
            <input type="text" name="bucket" lay-verify="required" placeholder="请输入空间名称" autocomplete="off" class="layui-input"  <?=$model->id ? 'disabled' : ''?>>
        </div>
    </div>

    <?php if(!$model->id): ?>
        <div class="layui-form-item">
            <label class="layui-form-label">区域</label>
            <div class="layui-input-block">
                <select name="region" lay-search="" lay-filter="region" lay-verify="required">
                    <option value="z0">华东</option>
                    <option value="z1">华北</option>
                    <option value="z2">华南</option>
                    <option value="na0">北美</option>
                    <option value="as0">东南亚</option>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <?php if($model->id): ?>
        <div class="layui-form-item">
            <label class="layui-form-label">空间绑定域名</label>
            <div class="layui-input-block">
                <textarea name="domains" placeholder="请输入空间绑定域名" class="layui-textarea" disabled></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">默认域名</label>
            <div class="layui-input-block">
                <select name="defaultDomain" lay-search="" lay-filter="defaultDomain" lay-verify="required">
                    <?php foreach ($domains as $domain): ?>
                        <option value="<?= $domain ?>"><?= $domain ?></option>
                    <?php endforeach; ?>
                </select>
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
<?php

use yii\helpers\Html;
use common\models\Bucket;

/* @var $this yii\web\View */
/* @var $model common\models\Prefix */
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">七牛空间</label>
        <div class="layui-input-block">
            <select name="bucketID" lay-search="" lay-filter="bucketID" lay-verify="required">
                <option value="0">通用</option>
                <?php foreach (Bucket::options() as $bucketID => $bucket): ?>
                    <option value="<?= $bucketID ?>"><?= $bucket ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">前缀</label>
        <div class="layui-input-block">
            <textarea name="prefix" placeholder="请输入前缀" class="layui-textarea"></textarea>
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
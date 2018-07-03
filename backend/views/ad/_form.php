<?php

use yii\helpers\Html;
use common\models\AdPosition;
use common\models\Ad;

/* @var $this yii\web\View */
/* @var $model \common\models\Ad */
/* @var $form yii\widgets\ActiveForm */

?>

<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">广告类型</label>
        <div class="layui-input-block">
            <select name="type" lay-filter="parent">
                <option value="0">图片</option>
                <option value="1">视频</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">广告位 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <select name="positionID" lay-filter="parent" lay-verify="required">
                <?php foreach (AdPosition::allOptions() as $positionID => $positionName): ?>
                    <option value="<?= $positionID ?>"><?= $positionName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">广告标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" placeholder="请输入广告标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <?= $this->render('@app/views/common/_upload', ['name'=>'content', 'label'=>'图片', 'value'=>$model->content, 'btnLabel'=>'上传图片', 'isImage'=>true, 'imgWidth'=>'200px', 'imgHeight'=>'100px']) ?>

    <div class="layui-form-item">
        <label class="layui-form-label">链接</label>
        <div class="layui-input-block">
            <input type="text" name="url" placeholder="请输入链接" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">打开方式</label>
        <div class="layui-input-block">
            <select name="openType" lay-filter="openType">
                <?php foreach (Ad::$labelOpenTypes as $openTypeValue => $openTypeLabel): ?>
                    <option value="<?= $openTypeValue ?>"><?= $openTypeLabel ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">是否显示</label>
        <div class="layui-input-block">
            <input type="checkbox" name="isShow" lay-skin="switch" lay-text="ON|OFF" lay-filter="isShow">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input type="text" name="sort" placeholder="请输入序号，数字越小越靠前" autocomplete="off" class="layui-input">
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

<?php

use yii\helpers\Html;
use common\models\ArticleCategory;
use common\models\Product;

/* @var $this yii\web\View */
/* @var $model \common\models\Article */
/* @var $form yii\widgets\ActiveForm */

$articleDetail = $model->articleDetail;
$content = $articleDetail ? $articleDetail->content : '';
?>

<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">文章分类 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <select name="categoryID" lay-search="" lay-filter="categoryID" lay-verify="required">
                <?php foreach (ArticleCategory::allOptions() as $categoryID => $categoryName): ?>
                    <option value="<?= $categoryID ?>"><?= $categoryName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关联产品 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <select name="productID" lay-search="" lay-filter="productID" lay-verify="required">
                <?php foreach (Product::allOptions() as $productID => $productName): ?>
                    <option value="<?= $productID ?>"><?= $productName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">文章标题 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required" placeholder="请输入文章标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <?= $this->render('@app/views/common/_upload', ['name'=>'thumb', 'label'=>'缩略图', 'value'=>$model->thumb, 'btnLabel'=>'上传图片', 'isImage'=>true]) ?>

    <div class="layui-form-item">
        <label class="layui-form-label">摘要 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <textarea name="brief" lay-verify="required" placeholder="请输入摘要" class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关键词 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="keywords" lay-verify="required" placeholder="请输入关键词" autocomplete="off" class="layui-input">
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

    <?= $this->render('@app/views/common/_ueditor', ['name'=>'content', 'label'=>'内容', 'value'=>$content]) ?>

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

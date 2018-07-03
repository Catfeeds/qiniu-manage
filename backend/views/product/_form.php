<?php

use yii\helpers\Html;
use common\models\ProductCategory;
use common\models\ProductCompany;

/* @var $this yii\web\View */
/* @var $model \common\models\Product */
/* @var $form yii\widgets\ActiveForm */
$productDetail = $model->productDetail;
$content = $productDetail ? $productDetail->content : '';
$process = $productDetail ? $productDetail->process : '';
$notification = $productDetail ? $productDetail->notification : '';
$productAttributes = $model->productAttributes;
foreach ($productAttributes as $attributesKey => $productAttribute){
    $productAttributes[$attributesKey] = $productAttribute->attributes;
}
?>

<form class="layui-form" lay-filter="layui-form" method="post">

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">保险名称 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" placeholder="请输入保险名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">保险编号 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="code" lay-verify="required" placeholder="请输入保险编号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">价格(元) <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="price" lay-verify="required" placeholder="请输入价格(元)" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">保险分类 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <select name="categoryID" lay-search="" lay-filter="categoryID" lay-verify="required">
                    <?php foreach (ProductCategory::allOptions() as $categoryID => $categoryName): ?>
                        <option value="<?= $categoryID ?>"><?= $categoryName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">保险公司 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <select name="companyID" lay-search="" lay-filter="companyID" lay-verify="required">
                    <?php foreach (ProductCompany::allOptions() as $companyID => $companyName): ?>
                        <option value="<?= $companyID ?>"><?= $companyName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">投保年龄 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="insurerAge" lay-verify="required" lay-verify="required" placeholder="请输入投保年龄" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">保障期限 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="guaranteePeriod" lay-verify="required" lay-verify="required" placeholder="请输入保障期限" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">职业限制 <span class="necessary">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="professionLimit" lay-verify="required" lay-verify="required" placeholder="请输入职业限制" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>

    <?= $this->render('@app/views/common/_upload', ['name'=>'thumb', 'label'=>'缩略图', 'value'=>$model->thumb, 'btnLabel'=>'上传图片', 'isImage'=>true]) ?>
    <?= $this->render('@app/views/common/_upload', ['name'=>'image', 'label'=>'头图', 'value'=>$model->image, 'btnLabel'=>'上传图片', 'isImage'=>true, 'loadJS'=>false, 'imgWidth'=>'200px']) ?>

    <div class="layui-form-item">
        <label class="layui-form-label">摘要 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <textarea name="brief" lay-verify="required" placeholder="请输入摘要" class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关键词 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="keywords" lay-verify="required" placeholder="请输入关键词,以英文逗号分隔" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">显示销量</label>
            <div class="layui-input-inline">
                <input type="text" name="saleAmount" placeholder="请输入显示销量" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" placeholder="请输入序号，数字越小越靠前" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">是否推荐</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="isRecommend" lay-skin="switch" lay-text="ON|OFF" lay-filter="isRecommend">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">是否上架</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="isShow" lay-skin="switch" lay-text="ON|OFF" lay-filter="isShow">
            </div>
        </div>
    </div>

    <?= $this->render('@app/views/common/_attributes', ['name'=>'attributes', 'label'=>'保障内容', 'initData'=>$productAttributes]) ?>

    <?= $this->render('@app/views/common/_ueditor', ['name'=>'content', 'label'=>'内容', 'value'=>$content]) ?>

    <?= $this->render('@app/views/common/_ueditor', ['name'=>'process', 'label'=>'服务详情', 'value'=>$process, 'loadJS'=>false]) ?>

    <?= $this->render('@app/views/common/_ueditor', ['name'=>'notification', 'label'=>'健康告知', 'value'=>$notification, 'loadJS'=>false]) ?>

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
            form.val("layui-form", <?= json_encode($model->getAttributes()) ?>);
            form.render(null, 'layui-form');
        });
    </script>
<?php $this->endBlock(); ?>

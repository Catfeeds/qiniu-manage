<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Menu;
use backend\models\Permission;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<form class="layui-form" lay-filter="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">上级菜单</label>
        <div class="layui-input-block">
            <select name="parentID" lay-search="" lay-filter="parent">
                <option value="0">---- 无 ----</option>
                <?php foreach (Menu::allMenuOptions() as $menu): ?>
                    <option value="<?= $menu['id'] ?>"><?php for($i=1; $i<$menu['level']; $i++): ?><?= '---- ' ?><?php endfor; ?><?= $menu['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">菜单名称 <span class="necessary">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item" id="form-icon" <?php if ($model->parentID): ?> style="display: none;" <?php endif; ?>>
        <label class="layui-form-label">显示图标</label>
        <div class="layui-input-block">
            <input type="text" name="icon" placeholder="请输入图标" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">左侧显示</label>
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

    <div class="layui-form-item">
        <label class="layui-form-label">关联权限</label>
        <div class="layui-input-block">
            <select name="permissionID" lay-search="" lay-filter="parent">
                <?php foreach (Permission::allPermissionOptions() as $permissionCategory): ?>
                    <optgroup label="<?=$permissionCategory['name']?>">
                        <?php foreach ($permissionCategory['permissions'] as $permission): ?>
                            <option value="<?=$permission['id']?>"><?=$permission['name']?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item layui-layout-admin">
        <div class="layui-input-block">
            <div class="layui-footer" style="left: 0;">
                <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['lay-submit'=>'','lay-filter'=>'menu-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success layui-btn' : 'btn btn-primary layui-btn']) ?>
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
            form.on("select(parent)", function (data) {
                if(data.value){
                    $("#form-icon").hide();
                }else{
                    $("#form-icon").show();
                }
            });
            form.render(null, 'layui-form');
        });
    </script>
<?php $this->endBlock(); ?>

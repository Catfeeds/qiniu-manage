<?php
use yii\helpers\Url;
use backend\models\PermissionCategory;
/* @var $permissionCategories PermissionCategory[] */
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            权限列表
            <a class="cmd-btn" layadmin-event='create' data-title="新建权限分类" data-url="<?= Url::to(['permission-category/create']) ?>">[新建权限分类]</a>
            <a class="cmd-btn" layadmin-event='create' data-title="新建权限" data-url="<?= Url::to(['permission/create']) ?>">[新建权限]</a>
        </div>
        <div class="layui-card-body">
            <div class="dd" id="list1">
                <ol class="dd-list">
                    <?php foreach ($permissionCategories as $permissionCategory): ?>
                        <li class="dd-item" data-id="<?= $permissionCategory->id ?>">
                            <div class="btn-collapse-expand" layadmin-event="tree_collapse">
                                <i class="fa fa-minus"></i>
                            </div>
                            <div class="dd-handle dd-nodrag">
                                <span><?=$permissionCategory->name?></span>
                                <a class="cmd-btn" layadmin-event='create' data-title="新建权限" data-url="<?= Url::to(['permission/create', 'categoryID'=>$permissionCategory->id]) ?>">[新建权限]</a>
                                <a class="pull-right cmd-btn" layadmin-event='delete' data-url="<?= Url::to(['permission-category/delete', 'id'=>$permissionCategory->id]) ?>" data-confirm="确定删除这个权限分类吗?">[删除]</a>
                                <a class="pull-right cmd-btn" layadmin-event='update' data-title="编辑权限分类" data-url="<?= Url::to(['permission-category/update', 'id'=>$permissionCategory->id]) ?>">[编辑]</a>
                                <a class="pull-right cmd-btn" layadmin-event='view' data-title="查看权限分类" data-url="<?= Url::to(['permission-category/view', 'id'=>$permissionCategory->id]) ?>">[查看]</a>
                            </div>
                            <?php if(count($permissionCategory->permissions)): ?>
                                <ol class="dd-list" style="display:block;">
                                    <?php foreach ($permissionCategory->permissions as $permission): ?>
                                        <li class="dd-item dd-item-permission" data-id="<?= $permission->id ?>">
                                            <div class="dd-handle dd-nodrag">
                                                <span><?=$permission->name?></span>
                                                <a class="pull-right cmd-btn" layadmin-event='delete' data-url="<?= Url::to(['permission/delete', 'id'=>$permission->id]) ?>" data-confirm="确定删除这个权限吗?">[删除]</a>
                                                <a class="pull-right cmd-btn" layadmin-event='update' data-title="编辑权限" data-url="<?= Url::to(['permission/update', 'id'=>$permission->id]) ?>">[编辑]</a>
                                                <a class="pull-right cmd-btn" layadmin-event='view' data-title="查看权限" data-url="<?= Url::to(['permission/view', 'id'=>$permission->id]) ?>">[查看]</a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                    <div class="clear"></div>
                                </ol>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?>
    <script>
        layui.config({
            base: '/layuiadmin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index']);
    </script>
<?php $this->endBlock(); ?>
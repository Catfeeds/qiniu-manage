<?php
use yii\helpers\Url;
use common\models\PermissionCategory;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['role/create']) ?>" data-title="新建角色" data-full="true" data-height="800px">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'name'}">角色名称</th>
                        <th lay-data="{field:'createTime'}">创建时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看角色" data-url="<?= Url::to(['role/view']) ?>" data-full="true" data-height="800px">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑角色" data-url="<?= Url::to(['role/update']) ?>" data-full="true" data-height="800px">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['role/delete']) ?>" data-confirm="确定删除这个角色吗?">[删除]</a>
            </script>
        </div>
    </div>
</div>
<?php $this->beginBlock('js_footer') ?>
<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table']);
</script>
<?php $this->endBlock(); ?>
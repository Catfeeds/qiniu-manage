<?php
use yii\helpers\Url;
use common\models\PermissionCategory;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['admin/create']) ?>" data-title="新建管理员" data-full="true" data-height="800px">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'username'}">账号</th>
                        <th lay-data="{field:'realName'}">真实姓名</th>
                        <th lay-data="{field:'roles'}">角色</th>
                        <th lay-data="{field:'createTime'}">创建时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看管理员" data-url="<?= Url::to(['admin/view']) ?>" data-full="true" data-height="800px">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑管理员" data-url="<?= Url::to(['admin/update']) ?>" data-full="true" data-height="800px">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['admin/delete']) ?>" data-confirm="确定删除这个管理员吗?">[删除]</a>
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
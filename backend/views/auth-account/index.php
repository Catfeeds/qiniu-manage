<?php
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['auth-account/create']) ?>" data-title="新建七牛授权账号">添加</button>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80}">ID</th>
                    <th lay-data="{field:'alias'}">别名</th>
                    <th lay-data="{field:'accessKey', width:400}">Access Key</th>
                    <th lay-data="{field:'secretKey', width:400}">Secret Key</th>
                    <th lay-data="{field:'createTime'}">创建时间</th>
                    <th lay-data="{toolbar:'#tableBar', width:260}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?= Url::to(['auth-account/view']) ?>" data-title="查看七牛授权账号" >[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['auth-account/update']) ?>" data-title="编辑七牛授权账号" >[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['auth-account/delete']) ?>" data-confirm="确定删除这个七牛授权账号吗?">[删除]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['auth-account/sync-buckets']) ?>" data-confirm="确定同步七牛空间吗?">[同步空间]</a>
            </script>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table']);
</script>
<?php $this->endBlock(); ?>
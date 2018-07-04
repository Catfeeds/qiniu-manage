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
                    <th lay-data="{field:'bucket', width:120}">空间名称</th>
                    <th lay-data="{field:'accountID', width:120}">七牛授权账号</th>
                    <th lay-data="{field:'domains', width:700}">空间绑定域名</th>
                    <th lay-data="{field:'defaultDomain', width:260}">默认域名</th>
                    <th lay-data="{field:'createTime'}">创建时间</th>
                    <th lay-data="{toolbar:'#tableBar', width:120}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?= Url::to(['bucket/view']) ?>" data-title="查看七牛空间" >[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['bucket/update']) ?>" data-title="编辑七牛空间" >[编辑]</a>
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
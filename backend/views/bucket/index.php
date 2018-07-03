<?php
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80}">ID</th>
                    <th lay-data="{field:'accountID', width:260}">七牛授权账号</th>
                    <th lay-data="{field:'bucket', width:120}">空间名称</th>
                    <th lay-data="{field:'domains', width:600}">空间绑定域名</th>
                    <th lay-data="{field:'defaultDomain', width:260}">默认域名</th>
                    <th lay-data="{field:'createTime'}">创建时间</th>
                    <th lay-data="{toolbar:'#tableBar', width:80}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?= Url::to(['bucket/view']) ?>" data-title="查看七牛空间" >[查看]</a>
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
<?php
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['product-company/create']) ?>" data-title="新建公司">添加</button>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'name', width:260}">名称</th>
                        <th lay-data="{field:'code', width:160}">编号</th>
                        <th lay-data="{field:'isShow', width:100, sort: true}">是否显示</th>
                        <th lay-data="{field:'description'}">备注</th>
                        <th lay-data="{toolbar:'#tableBar', width:120}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['product-company/update']) ?>" data-title="编辑公司" >[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['product-company/delete']) ?>" data-confirm="确定删除这个公司吗?">[删除]</a>
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
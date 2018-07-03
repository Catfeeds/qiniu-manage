<?php
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['ad-position/create']) ?>" data-title="新建广告位">添加</button>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'key', width:260}">标示</th>
                        <th lay-data="{field:'name', width:260}">标题</th>
                        <th lay-data="{field:'isShow', width:100, sort: true}">是否显示</th>
                        <th lay-data="{field:'createTime'}">发布时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看广告位" data-url="<?= Url::to(['ad-position/view']) ?>">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['ad-position/update']) ?>" data-title="编辑广告位" >[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['ad-position/delete']) ?>" data-confirm="确定删除这个广告位吗?">[删除]</a>
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
<?php
use yii\helpers\Url;
use common\models\PermissionCategory;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['ad/create']) ?>" data-title="新建广告">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'positionID', width:260}">广告位</th>
                        <th lay-data="{field:'title'}">标题</th>
                        <th lay-data="{field:'content'}">图片</th>
                        <th lay-data="{field:'openType'}">打开方式</th>
                        <th lay-data="{field:'isShow', width:100, sort: true}">是否显示</th>
                        <th lay-data="{field:'createTime'}">发布时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看广告" data-url="<?= Url::to(['ad/view']) ?>">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑广告" data-url="<?= Url::to(['ad/update']) ?>">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['ad/delete']) ?>" data-confirm="确定删除这篇广告吗?">[删除]</a>
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
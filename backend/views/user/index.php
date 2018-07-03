<?php
use yii\helpers\Url;
?>
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                    <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'phone', width:260}">手机号</th>
                        <th lay-data="{field:'status', width:100, sort: true}">账号状态</th>
                        <th lay-data="{field:'createTime'}">注册时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                    </thead>
                </table>
                <script type="text/html" id="tableBar">
                    <a class="cmd-btn" lay-event='view' data-title="查看用户" data-url="<?= Url::to(['user/view']) ?>" data-full="true" data-height="800px">[查看]</a>
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
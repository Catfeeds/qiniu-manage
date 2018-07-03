<?php
use yii\helpers\Url;
use common\models\Version;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入版本名称" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <select name="typeID">
                            <option value="">请选择版本类型</option>
                            <?php foreach (Version::$labelTypes as $typeID => $typeName): ?>
                                <option value="<?= $typeID ?>"><?= $typeName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-filter="form-filter" layadmin-event="form_search" type="button">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['version/create']) ?>" data-title="新建版本" data-full="true" data-height="800px">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'name'}">版本名称</th>
                        <th lay-data="{field:'typeID'}">版本类型</th>
                        <th lay-data="{field:'version'}">版本号</th>
                        <th lay-data="{field:'adminID'}">管理员</th>
                        <th lay-data="{field:'info'}">说明</th>
                        <th lay-data="{field:'url'}">下载</th>
                        <th lay-data="{field:'createTime'}">发布时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看版本" data-url="<?= Url::to(['version/view']) ?>" data-full="true" data-height="800px">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑版本" data-url="<?= Url::to(['version/update']) ?>" data-full="true" data-height="800px">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['version/delete']) ?>" data-confirm="确定删除这个版本吗?">[删除]</a>
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
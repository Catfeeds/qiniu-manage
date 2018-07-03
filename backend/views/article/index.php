<?php
use yii\helpers\Url;
use common\models\ArticleCategory;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="title" placeholder="请输入文章标题" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <select name="categoryID">
                            <option value="">请选择文章分类</option>
                            <?php foreach (ArticleCategory::allOptions() as $categoryID => $categoryName): ?>
                                <option value="<?= $categoryID ?>"><?= $categoryName ?></option>
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
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['article/create']) ?>" data-title="新建文章" data-full="true" data-height="800px">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'title'}">标题</th>
                        <th lay-data="{field:'categoryID', width:260}">文章分类</th>
                        <th lay-data="{field:'isShow', width:100, sort: true}">是否显示</th>
                        <th lay-data="{field:'createTime'}">发布时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看文章" data-url="<?= Url::to(['article/view']) ?>" data-full="true" data-height="800px">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑文章" data-url="<?= Url::to(['article/update']) ?>" data-full="true" data-height="800px">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['article/delete']) ?>" data-confirm="确定删除这篇文章吗?">[删除]</a>
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
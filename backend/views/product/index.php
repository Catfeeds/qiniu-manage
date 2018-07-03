<?php
use yii\helpers\Url;
use common\models\ProductCategory;
use common\models\ProductCompany;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入保险名称" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <select name="categoryID">
                            <option value="">请选择保险分类</option>
                            <?php foreach (ProductCategory::allOptions() as $categoryID => $categoryName): ?>
                                <option value="<?= $categoryID ?>"><?= $categoryName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="companyID">
                            <option value="">请选择保险公司</option>
                            <?php foreach (ProductCompany::allOptions() as $companyID => $companyName): ?>
                                <option value="<?= $companyID ?>"><?= $companyName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="isShow">
                            <option value="">请选择保险状态</option>
                            <option value="1">上架</option>
                            <option value="0">下架</option>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="isRecommend">
                            <option value="">请选择是否推荐</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
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
                <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['product/create']) ?>" data-title="新建保险" data-full="true" data-height="800px">添加</button>
            </div>
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'name'}">名称</th>
                        <th lay-data="{field:'categoryID', width:260}">保险分类</th>
                        <th lay-data="{field:'companyID', width:260}">保险公司</th>
                        <th lay-data="{field:'price'}">价格(元)</th>
                        <th lay-data="{field:'isShow', width:100, sort: true}">状态</th>
                        <th lay-data="{field:'isRecommend', sort: true}">推荐</th>
                        <th lay-data="{field:'createTime'}">发布时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:220}">操作</th>
                    </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-title="查看保险" data-url="<?= Url::to(['product/view']) ?>" data-full="true" data-height="800px">[查看]</a>
                <a class="cmd-btn" lay-event='update' data-title="编辑保险" data-url="<?= Url::to(['product/update']) ?>" data-full="true" data-height="800px">[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['product/delete']) ?>" data-confirm="确定删除这个保险吗?">[删除]</a>
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
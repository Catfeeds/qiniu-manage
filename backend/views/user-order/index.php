<?php
use yii\helpers\Url;
?>
    <div class="layui-fluid">
        <div class="layui-card">
            <form class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="order" placeholder="请输入订单号" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">请选择订单状态</option>
                                <option value="0">待支付</option>
                                <option value="1">已支付</option>
                                <option value="-1">已关闭</option>
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
                <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                    <thead>
                    <tr>
                        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                        <th lay-data="{field:'order', width:280}">订单号</th>
                        <th lay-data="{field:'userID', width:160}">用户</th>
                        <th lay-data="{field:'productID', width:220}">商品</th>
                        <th lay-data="{field:'amount'}">保额(元)</th>
                        <th lay-data="{field:'premium'}">保费(元)</th>
                        <th lay-data="{field:'status'}">状态</th>
                        <th lay-data="{field:'createTime', width:220}">下单时间</th>
                        <th lay-data="{toolbar:'#tableBar', width:120}">操作</th>
                    </tr>
                    </thead>
                </table>
                <script type="text/html" id="tableBar">
                    <a class="cmd-btn" lay-event='view' data-title="查看订单" data-url="<?= Url::to(['user-order/view']) ?>" data-full="true" data-height="800px">[查看]</a>
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
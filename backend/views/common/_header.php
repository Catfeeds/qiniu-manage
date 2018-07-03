<?php
use yii\helpers\Url;
?>
<div class="layui-header">
    <!-- 头部区域 -->
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a lay-href="<?= Url::to(['home/dashboard']) ?>" target="_blank" title="首页">
                <i class="layui-icon layui-icon-home"></i>
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新" id="tab-refresh">
                <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
        </li>
    </ul>
    <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
                <i class="layui-icon layui-icon-theme"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
                <i class="layui-icon layui-icon-note"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
                <i class="layui-icon layui-icon-screen-full"></i>
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
                <cite><?= Yii::$app->session->get('admin')['realName'] ?></cite>
            </a>
            <dl class="layui-nav-child">
                <dd><a lay-href="<?= Url::to(['account/profile']) ?>">基本资料</a></dd>
                <dd><a lay-href="<?= Url::to(['account/password']) ?>">修改密码</a></dd>
                <hr>
                <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
            </dl>
        </li>

        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;"><i class="layui-icon layui-icon-more-vertical"></i></a>
        </li>
        <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;"><i class="layui-icon layui-icon-more-vertical"></i></a>
        </li>
    </ul>
</div>
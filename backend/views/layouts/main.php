<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->params['app_name'] ?> - <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="layui-layout-body">
<?php $this->beginBody() ?>

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <!-- 头部区域 -->
        <?= $this->render('@app/views/common/_header.php') ?>

        <!-- 侧边菜单 -->
        <?= $this->render('@app/views/common/_side.php') ?>

        <!-- 页面标签 -->
        <?= $this->render('@app/views/common/_tab.php') ?>

        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <?= $content ?>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>
<?php $this->endBody() ?>

<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>

<?php if(isset($this->blocks) && is_array($this->blocks)): ?>
    <?php foreach ($this->blocks as $block): ?>
        <?= $block ?>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>


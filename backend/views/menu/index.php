<?php
use yii\helpers\Url;
use backend\models\Menu;
/* @var $parentMenus Menu[] */
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            菜单列表
            <a class="cmd-btn" layadmin-event='create' data-title="新建菜单" data-url="<?= Url::to(['menu/create']) ?>" data-full="true" data-height="800px">[新建菜单]</a>
        </div>
        <div class="layui-card-body">
            <div class="dd" id="list1">
                <ol class="dd-list">
                    <?php foreach ($parentMenus as $parentMenu): ?>
                        <?= $this->render('_menu', [
                            'menu' => $parentMenu,
                        ]) ?>
                    <?php endforeach; ?>
                </ol>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?>
    <script>
        layui.config({
            base: '/layuiadmin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index']);
    </script>
<?php $this->endBlock(); ?>
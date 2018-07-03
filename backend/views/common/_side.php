<?php
use yii\helpers\Url;
use backend\models\Menu;
use backend\services\AuthService;

/* @var $parentMenus Menu[] */

$parentMenus = Menu::find()->where(['parentID'=>0, 'isShow'=>1])->orderBy('sort asc')->all();
$adminPermissions = AuthService::getAdminPermissions();
?>
<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo" lay-href="home/console.html">
            <span><?= Yii::$app->params['app_name'] ?></span>
        </div>
        <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
            <?php foreach ($parentMenus as $menuKey => $parentMenu): ?>
                <?php
                    $menuPermission = $parentMenu->permission;
                    $menuUrl = $menuPermission ? Url::to([$menuPermission->controller.'/'.$menuPermission->action]) : '';
                ?>
                <?php if($parentMenu->isShow && (!$parentMenu->permissionID || in_array($parentMenu->permissionID, $adminPermissions))): ?>
                    <li class="layui-nav-item <?= $menuKey === 0 ? 'layui-nav-itemed' : '' ?>">
                        <a <?= count($parentMenu->children) ? 'href="javascript:;"' : 'lay-href="'.$menuUrl.'"' ?> lay-tips="<?= $parentMenu->name ?>">
                            <i class="layui-icon <?= $parentMenu->icon ?>"></i>
                            <cite><?= $parentMenu->name ?></cite>
                        </a>
                        <?php if(count($parentMenu->children)): ?>
                            <dl class="layui-nav-child">
                                <?php foreach ($parentMenu->children as $submenu): ?>
                                    <?= $this->render('@app/views/common/_menu.php', ['menu'=>$submenu, 'adminPermissions'=>$adminPermissions]) ?>
                                <?php endforeach; ?>
                            </dl>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?>
    <script>
        $(".layui-side-menu dl.layui-nav-child").each(function () {
            if($(this).find("dd a[href != 'javascript:;']").length <= 0){
                $(this.remove());
            }
        })
        $(".layui-side-menu li.layui-nav-item").each(function () {
            if($(this).find("dd a[href != 'javascript:;']").length <= 0){
                $(this.remove());
            }
        })
        $(".layui-side-menu").show();
    </script>
<?php $this->endBlock(); ?>
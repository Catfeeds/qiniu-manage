<?php

use yii\helpers\Url;

/* @var $menu backend\models\Menu */
/* @var $adminPermissions array */

$menuPermission = $menu->permission;
$menuUrl = $menuPermission ? Url::to([$menuPermission->controller.'/'.$menuPermission->action]) : '';
?>
<?php if($menu->isShow && (!$menu->permissionID || in_array($menu->permissionID, $adminPermissions))): ?>
    <dd>
        <?php if(count($menu->children)): ?>
            <a href="javascript:;"><?= $menu->name ?></a>
            <dl class="layui-nav-child">
                <?php foreach ($menu->children as $submenu): ?>
                    <?php if($submenu->isShow): ?>
                        <?= $this->render('@app/views/common/_menu.php', ['menu'=>$submenu, 'adminPermissions'=>$adminPermissions]) ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </dl>
        <?php else: ?>
            <a lay-href="<?= $menuUrl ?>"><?= $menu->name ?></a>
        <?php endif; ?>
    </dd>
<?php endif; ?>
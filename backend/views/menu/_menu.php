<?php

use yii\helpers\Url;

/* @var $menu backend\models\Menu */
?>

<li class="dd-item" data-id="<?= $menu->id ?>">
    <?php if(count($menu->children)): ?>
        <div class="btn-collapse-expand" layadmin-event="tree_collapse">
            <i class="fa fa-minus"></i>
        </div>
    <?php endif; ?>
    <div class="dd-handle dd-nodrag">
        <i class="layui-icon <?= $menu['icon'] ?>"></i>
        <a><?= $menu['name'] ?></a>
        <span class="btn-is-show btn-<?= $menu['isShow'] === 1 ? 'show' : 'hide' ?>">[<?= $menu['isShow'] === 1 ? '显示' : '隐藏' ?>]</span>
        <a class="cmd-btn" layadmin-event='create' data-title="新建子菜单" data-url="<?= Url::to(['menu/create', 'parentID'=>$menu->id]) ?>" data-full="true" data-height="800px">[新建子菜单]</a>
        <?php if($permission = $menu->permission): ?>
            <span class="btn-show">[<?=$permission->controller.'/'.$permission->action?>]</span>
        <?php endif; ?>
        <a class="pull-right cmd-btn" layadmin-event='delete' data-url="<?= Url::to(['menu/delete', 'id'=>$menu->id]) ?>" data-confirm="确定删除这个菜单吗?">[删除]</a>
        <a class="pull-right cmd-btn" layadmin-event='update' data-title="编辑菜单" data-url="<?= Url::to(['menu/update', 'id'=>$menu->id]) ?>" data-full="true" data-height="800px">[编辑]</a>
        <a class="pull-right cmd-btn" layadmin-event='view' data-title="查看菜单" data-url="<?= Url::to(['menu/view', 'id'=>$menu->id]) ?>">[查看]</a>
    </div>
    <?php if(count($menu->children)): ?>
        <ol class="dd-list" style="display:block;">
            <?php foreach ($menu->children as $submenu): ?>
                <?= $this->render('_menu', [
                    'menu' => $submenu,
                ]) ?>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</li>
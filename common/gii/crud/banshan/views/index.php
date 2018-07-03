<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator common\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$modelClass = $generator->modelClass;
$controllerID = $generator->getControllerID();
$model = new $modelClass();
$modelName = $generator->modelName;
$columns = array_values($modelClass::getTableSchema()->getColumnNames());
echo "<?php\n";
?>
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?='<?= Url::to([\''.$controllerID.'/create\']) ?>'?>" data-title="新建<?=$modelName?>">添加</button>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?='<?= Url::current()?>'?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
<?php foreach ($columns as $column): ?>
                    <th lay-data="{field:'<?=$column?>'}"><?=$model->attributeLabels()[$column]?></th>
<?php endforeach; ?>
                    <th lay-data="{toolbar:'#tableBar'}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?='<?= Url::to([\''.$controllerID.'/view\']) ?>'?>" data-title="查看<?=$modelName?>" >[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?='<?= Url::to([\''.$controllerID.'/update\']) ?>'?>" data-title="编辑<?=$modelName?>" >[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?='<?= Url::to([\''.$controllerID.'/delete\']) ?>'?>" data-confirm="确定删除这个<?=$modelName?>吗?">[删除]</a>
            </script>
        </div>
    </div>
</div>

<?='<?php $this->beginBlock(\'js_footer\') ?>'?>
<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table']);
</script>
<?='<?php $this->endBlock(); ?>'?>
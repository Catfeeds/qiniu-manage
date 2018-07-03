<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$modelName = $generator->modelName;
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
$columns = array_values($class::getTableSchema()->getColumnNames());
$controllerID = $generator->getControllerID();
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use common\libs\Session;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{

    /**
     * <?=$modelName?>列表.
     *
     * @return string
     */
    public function actionIndex()
    {
        if($this->request()->isAjax){
<?php if (in_array('createTime', $columns)): ?>
            $options = [
                'join' => [],
                'view' => 'index',
                'like' => [],
                'where' => [],
                'order' => 'createTime desc'
            ];
<?php else: ?>
            $options = [
                'join' => [],
                'view' => 'index',
                'like' => [],
                'where' => [],
                'order' => ''
            ];
<?php endif; ?>
            $columns = [<?= "'".implode("', '", $columns)."'" ?>];
            $this->baseIndex(<?= $modelClass ?>::class, $columns, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * <?=$modelName?>详情.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = [<?= "'".implode("', '", $columns)."'" ?>];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 创建<?=$modelName?>.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $post = Yii::$app->request->post();
<?php if (in_array('isShow', $columns)): ?>
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
<?php endif; ?>
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建<?=$modelName?>成功');
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->baseForm($model, '<?=$controllerID?>/_form');
        }
    }

    /**
     * 编辑<?=$modelName?>.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
<?php if (in_array('isShow', $columns)): ?>
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
<?php endif; ?>
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑<?=$modelName?>成功');
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->baseForm($model, '<?=$controllerID?>/_form');
        }
    }

    /**
     * 删除<?=$modelName?>.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Session::success('删除<?=$modelName?>成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php
namespace backend\controllers;

use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * User controller
 */
class UserController extends ContentController
{

    /**
     * 用户列表
     *
     * @return string
     */
    public function actionIndex()
    {
        if($this->request()->isAjax){
            $options = [
                'join' => [],
                'view' => 'index',
                'like' => [],
                'where' => [],
            ];
            $column = [
                'id',
                'phone:decryptPhone',
                'userAuth:userAuth',
                'status:userStatus',
                'createTime:dateTime',
            ];
            $this->baseIndex(User::class, $column, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 详情页面
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $columns = [
            'id',
            'phone:decryptPhone',
            'userAuth:userAuth',
            'status:userStatus',
            'createTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php
namespace backend\controllers;

use common\models\User;
use common\models\UserOrder;
use yii\web\NotFoundHttpException;

/**
 * UserOrder controller
 */
class UserOrderController extends ContentController
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
                'order' => 'createTime desc'
            ];
            $column = [
                'id',
                'userID:commonUser',
                'productID:commonProduct',
                'order',
                'amount:money',
                'premium:money',
                'status:orderStatus',
                'createTime:dateTime',
            ];
            $this->baseIndex(UserOrder::class, $column, $options);
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
            'userID:commonUser',
            'productID:commonProduct',
            'order',
            'amount:money',
            'premium:money',
            'productCode',
            'insBeginDate',
            'insEndDate',
            'payOrderNo',
            'productName',
            'policyNo',
            'epolicyUrl',
            'payCallbackRemark',
            'status:orderStatus',
            'createTime:dateTime',
            'updateTime:dateTime',
            'hebaoTime:dateTime',
            'payTime:dateTime',
            'chengbaoTime:dateTime',
            'closeTime:dateTime',
        ];
        $holderColumns = [
            'id',
            'name',
            'cardNo:identity',
            'birthday'
        ];
        $insuredColumns = [
            'id',
            'name',
            'cardNo:identity',
            'birthday',
            'relationship:relationship',
        ];
        return $this->render('view', ['model'=>$this->findModel($id), 'columns'=>$columns, 'holderColumns' => $holderColumns, 'insuredColumns' => $insuredColumns]);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = UserOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

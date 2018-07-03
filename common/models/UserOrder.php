<?php

namespace common\models;

use common\libs\Cache;
use common\libs\CommonFunction;

/**
 * This is the model class for table "{{%user_order}}".
 *
 * @property integer $id
 * @property integer $userID
 * @property integer $productID
 * @property string $order
 * @property integer $status
 * @property integer $amount
 * @property integer $premium
 * @property string $productCode
 * @property string $productName
 * @property string $insBeginDate
 * @property string $insEndDate
 * @property string $payOrderNo
 * @property string $policyNo
 * @property string $epolicyUrl
 * @property string $payCallbackRemark
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $hebaoTime
 * @property integer $chengbaoTime
 * @property integer $payTime
 * @property integer $cancelTime
 * @property integer $closeTime
 *
 * @property UserOrderHolder $holder
 * @property UserOrderInsured $insured
 */
class UserOrder extends \yii\db\ActiveRecord
{

    public static $labelStatus = [
        '-1' => '已关闭',
        '0' => '待支付',
        '1' => '已支付'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'productID', 'order', 'status', 'amount', 'premium', 'productCode', 'productName', 'insBeginDate', 'insEndDate'], 'required'],
            [['userID', 'productID', 'amount', 'premium', 'createTime', 'updateTime', 'hebaoTime', 'payTime', 'chengbaoTime', 'cancelTime', 'closeTime'], 'integer'],
            [['order', 'payOrderNo', 'policyNo'], 'string', 'max' => 50],
            [['epolicyUrl', 'payCallbackRemark'], 'string', 'max' => 225],
            [['status'], 'integer', 'max' => 1],
            [['order'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单ID',
            'userID' => '用户ID',
            'productID' => '商品ID',
            'order' => '订单号',
            'status' => '订单的状态',
            'amount' => '保额',
            'premium' => '保费',
            'productCode' => '保险产品编号',
            'productName' => '保险产品名称',
            'insBeginDate' => '保险起期',
            'insEndDate' => '保险止期',
            'policyNo' => '保单号',
            'epolicyUrl' => '电子保单下载链接',
            'payCallbackRemark' => '支付回调说明',
            'createTime' => '创建订单时间',
            'updateTime' => '更新时间',
            'hebaoTime' => '确认订单时间',
            'payTime' => '支付时间',
            'chengbaoTime' => '承保时间',
            'cancelTime' => '取消时间',
            'closeTime' => '订单失败关闭时间',
            'holder' => '投保人',
            'insured' => '被保人',
        ];
    }

    /**
    * 写入数据库前处理
    *
    * @param bool $insert
    * @return bool
    */
    public function beforeSave($insert)
    {
        if($insert){
            $this->createTime = $this->updateTime = time();
        } else {
            $this->updateTime = time();
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->resetCache();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $this->resetCache();
        parent::afterDelete();
    }

    /**
     * 重置缓存
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function resetCache(){
        $cacheName = 'ORDER_'.$this->id;
        Cache::clear($cacheName);
    }

    /**
     * 处理订单数据
     *
     * @param $order
     * @return mixed
     */
    public static function combineOrder($order){
        $order['amount'] /= 100;
        $order['premium'] /= 100;
        $holder = UserOrderHolder::find()->where(['orderID'=>$order['id']])->asArray()->one();
        $holder['cardNo'] = CommonFunction::dealIdentity(CommonFunction::decrypt($holder['cardNo']));
        $insured = UserOrderInsured::find()->where(['orderID'=>$order['id']])->asArray()->one();
        $insured['cardNo'] = CommonFunction::dealIdentity(CommonFunction::decrypt($insured['cardNo']));
        $order['holder'] = $holder;
        $order['insured'] = $insured;
        return $order;
    }

    /**
     * 根据订单号查询订单
     *
     * @param $order
     * @return array|null|UserOrder
     */
    public static function getByOrder($order){
        $userOrder = UserOrder::find()->where(['order'=>$order])->one();
        return $userOrder;
    }

    public function getHolder(){
        return $this->hasOne(UserOrderHolder::class, ['orderID'=>'id']);
    }

    public function getInsured(){
        return $this->hasOne(UserOrderInsured::class, ['orderID'=>'id']);
    }
}

<?php

namespace common\models;

/**
 * This is the model class for table "{{%user_order_holder}}".
 *
 * @property integer $id
 * @property integer $orderID
 * @property string $name
 * @property string $email
 * @property string $cardType
 * @property string $cardNo
 * @property string $birthday
 * @property string $mobile
 * @property string $jobCode
 * @property string $address
 * @property string $relationship
 */
class UserOrderHolder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_order_holder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderID', 'name', 'cardNo', 'birthday', 'mobile'], 'required'],
            [['orderID'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 30],
            [['cardType', 'birthday', 'mobile', 'jobCode'], 'string', 'max' => 11],
            [['address', 'cardNo'], 'string', 'max' => 100],
            [['relationship'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderID' => '订单ID',
            'name' => '姓名',
            'email' => '邮箱',
            'cardType' => '证件类型,默认身份证',
            'cardNo' => '身份证号',
            'birthday' => '投保人生日',
            'mobile' => '投保人手机号',
            'jobCode' => '职业',
            'address' => '联系地址',
            'relationship' => '与被保险人关系',
        ];
    }

}

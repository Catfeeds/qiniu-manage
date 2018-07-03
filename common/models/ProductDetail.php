<?php

namespace common\models;

/**
 * This is the model class for table "{{%product_detail}}".
 *
 * @property integer $productID
 * @property string $content
 * @property string $process
 * @property string $notification
 *
 * @property Product $product
 */
class ProductDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productID', 'content', 'process', 'notification'], 'required'],
            [['productID'], 'integer'],
            [['content', 'process', 'notification'], 'string'],
            [['productID'], 'unique'],
            [['productID'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['productID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productID' => '商品ID',
            'content' => '商品详情',
            'process' => '服务详情',
            'notification' => '健康告知',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productID']);
    }
}

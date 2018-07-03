<?php

namespace common\models;

/**
 * This is the model class for table "{{%product_attribute}}".
 *
 * @property integer $productID
 * @property string $key
 * @property string $name
 * @property string $value
 *
 * @property Product $product
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productID', 'key', 'name', 'value'], 'required'],
            [['productID'], 'integer'],
            [['key'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productID' => '保险ID',
            'key' => 'Key值',
            'name' => '属性名称',
            'value' => '属性值',
        ];
    }

    /**
     * 所属商品
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(){
        return $this->hasOne(Product::class, ['id'=>'productID']);
    }

}

<?php

namespace common\models;

/**
 * This is the model class for table "{{%prefix}}".
 *
 * @property integer $id
 * @property integer $accountID
 * @property integer $bucketID
 * @property string $prefix
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property Bucket $bucket
 */
class Prefix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prefix}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefix'], 'required'],
            [['accountID', 'bucketID', 'createTime', 'updateTime'], 'integer'],
            [['prefix'], 'string', 'max' => 100],
            [['bucketID', 'prefix'], 'unique', 'targetAttribute' => ['bucketID', 'prefix'], 'message' => '空间下已有该前缀'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accountID' => '授权账号',
            'bucketID' => '七牛空间ID',
            'prefix' => '前缀',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
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

    /**
     * 七牛空间
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBucket(){
        return $this->hasOne(Bucket::class, ['id'=>'bucketID']);
    }
}

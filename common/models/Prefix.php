<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%prefix}}".
 *
 * @property integer $id
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
            [['bucketID', 'prefix'], 'required'],
            [['bucketID', 'createTime', 'updateTime'], 'integer'],
            [['prefix'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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

    /**
     * 保存后操作
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->resetCache();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 删除后操作
     */
    public function afterDelete()
    {
        $this->resetCache();
        parent::afterDelete();
    }

    /**
     * 重置缓存
     */
    public function resetCache(){
        Cache::set('PREFIX_'.$this->id, $this->attributes);
    }

    /**
     * 根据ID查找
     *
     * @param $id
     * @param bool $isModel
     * @return array|null|\common\models\Bucket
     */
    public static function get($id, $isModel=false){
        if($isModel){
            return self::find()->where(['id'=>$id])->one();
        }else{
            $cacheName = 'PREFIX_'.$id;
            $cache = Cache::get($cacheName);
            if($cache === false){
                $cache = self::find()->where(['id'=>$id])->asArray()->one();
                Cache::set($cacheName, $cache);
            }
            return $cache;
        }
    }
}

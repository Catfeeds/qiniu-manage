<?php

namespace common\models;
use common\libs\Cache;
use common\libs\CommonFunction;

/**
 * This is the model class for table "{{%auth_account}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $accessKey
 * @property string $secretKey
 * @property integer $createTime
 * @property integer $updateTime
 */
class AuthAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'accessKey', 'secretKey'], 'required'],
            [['alias', 'accessKey', 'secretKey'], 'unique'],
            [['createTime', 'updateTime'], 'integer'],
            [['accessKey', 'secretKey'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => '别名',
            'accessKey' => 'Access Key',
            'secretKey' => 'Secret Key',
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
        Cache::set('AUTH_ACCOUNT_'.$this->id, $this->attributes);
        $cache = self::find()->select('accessKey')->indexBy('id')->column();
        Cache::set('ALL_AUTH_ACCOUNT_OPTIONS', $cache);
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
            $cacheName = 'AUTH_ACCOUNT_'.$id;
            $cache = Cache::get($cacheName);
            if($cache === false){
                $cache = self::find()->where(['id'=>$id])->asArray()->one();
                Cache::set($cacheName, $cache);
            }
            return $cache;
        }
    }

    /**
     * 选项
     *
     * @return array|mixed
     */
    public static function options(){
        $cacheName = 'ALL_AUTH_ACCOUNT_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->select('accessKey')->indexBy('id')->column();
            Cache::set($cacheName, $cache);
        }
        foreach ($cache as $key => $value){
            $cache[$key] = CommonFunction::dealQiniuAccount($value);
        }
        return $cache;
    }
}

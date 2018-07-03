<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $encrypt
 * @property integer $status
 * @property string $loginPassword
 * @property string $gesturePassword
 * @property string $dealPassword
 * @property string $phone
 * @property integer $place
 * @property string $appKey
 * @property string $openID
 * @property integer $sourceID
 * @property integer $createTime
 * @property integer $authTime
 *
 * @property UserAuth $userAuth
 */
class User extends \yii\db\ActiveRecord
{

    public static $labelStatus = [
        '-1' => '黑名单',
        '0' => '未启用',
        '1' => '正常',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createTime', 'authTime'], 'integer'],
            [['authTime'], 'required'],
            [['encrypt'], 'string', 'max' => 6],
            [['status', 'place', 'sourceID'], 'string', 'max' => 1],
            [['loginPassword', 'gesturePassword', 'dealPassword'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 30],
            [['appKey'], 'string', 'max' => 50],
            [['openID'], 'string', 'max' => 100],
            [['phone'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'encrypt' => '加密盐',
            'status' => '账号状态',
            'loginPassword' => '登录密码',
            'gesturePassword' => '手势密码',
            'dealPassword' => '交易密码',
            'phone' => '手机号码',
            'place' => '注册渠道1安卓 2ios3、win4pc5wap 6mac',
            'appKey' => 'app授权key',
            'openID' => 'openID',
            'sourceID' => '最后登录设备',
            'createTime' => '注册时间',
            'authTime' => '认证时间',
            'userAuth' => '认证状态'
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
            $this->createTime = time();
        }
        return parent::beforeSave($insert);
    }

    public function getUserAuth(){
        return $this->hasOne(UserAuth::class, ['userID'=>'id']);
    }

    /**
     * 从缓存中读取用户简单信息
     *
     * @param $userID
     * @return array
     */
    public static function get($userID){
        $cacheName = 'USER_'.$userID;
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->where(['id'=>$userID])->asArray()->one();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}

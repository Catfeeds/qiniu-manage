<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "bx_version".
 *
 * @property integer $id
 * @property integer $typeID
 * @property integer $version
 * @property string $name
 * @property integer $adminID
 * @property integer $createTime
 * @property string $info
 * @property string $url
 */
class Version extends \yii\db\ActiveRecord
{
    public static $labelTypes = ['1'=>'安卓', '2'=>'PC'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bx_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version', 'info'], 'required'],
            [['version', 'adminID', 'createTime'], 'integer'],
            [['info'], 'string'],
            [['typeID'], 'integer', 'max' => 1],
            [['name'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'typeID' => '1安卓2pc',
            'version' => '版本号',
            'name' => '版本名称',
            'adminID' => '管理员id',
            'createTime' => '创建时间',
            'info' => '描述',
            'url' => '地址',
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
        $admin = \Yii::$app->session->get('admin');
        $this->adminID = $admin['id'];
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

    public function resetCache(){
        $cache_name = 'VERSION_'.$this->typeID;
        $latest = Version::find()->where(['typeID'=>$this->typeID])->orderBy('id desc')->limit(1)->asArray()->one();
        if(!$latest){
            $cache = ['status'=>1];
        }else
            $cache = ['v'=>$latest['version'], 'status'=>1, 'version'=>$latest['version'], 'name'=>$latest['name'],'info'=>$latest['info'],'url'=>$latest['url']];
        Cache::set($cache_name, $cache);
    }
}

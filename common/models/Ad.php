<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%ad}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $positionID
 * @property string $title
 * @property string $url
 * @property string $content
 * @property string $openType
 * @property integer $isShow
 * @property integer $sort
 * @property integer $createTime
 * @property integer $updateTime
 */
class Ad extends \yii\db\ActiveRecord
{
    public static $labelTypes = ['图片', '视频'];
    public static $labelOpenTypes = [
        '_blank'=>'新标签',
        '_self' => '当前标签',
        '_parent' => '父级标签',
        '_top' => '新窗口'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->type = 0;
        $this->isShow = 1;
        $this->openType = '_blank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['positionID'], 'integer'],
            [['openType'], 'string'],
            [['type', 'isShow'], 'integer', 'max' => 1],
            [['title'], 'string', 'max' => 50],
            [['sort', 'isShow'], 'default', 'value' => 1],
            [['url', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'type' => '广告类型',
            'positionID' => '广告位ID',
            'title' => '广告名称',
            'url' => '广告链接',
            'content' => '图片',
            'openType' => '打开类型',
            'isShow' => '广告状态1：显示，0：不显示',
            'sort' => '排序',
            'createTime' => '创建时间',
            'updateTime' => '修改时间',
        ];
    }

    /**
     * 校验前处理数据
     *
     * @return bool
     */
    public function beforeValidate()
    {
        $this->isShow === 'on' && $this->isShow = 1;
        ($this->isShow === 'off' || is_null($this->isShow) ) && $this->isShow = 0;
        return parent::beforeValidate();
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

    public function resetCache(){
        $adPosition = AdPosition::findOne(['id'=>$this->positionID]);
        $cache = self::find()->where(['positionID'=>$adPosition['id']])->orderBy('sort')->asArray()->all();
        $cacheName = 'ADS_POSITION_'.$adPosition['key'];
        Cache::set($cacheName, $cache);
    }

    /**
     * 指定广告位的所有广告
     *
     * @param $positionKey
     * @return array|缓存值|\yii\db\ActiveRecord[]
     */
    public static function getAds($positionKey){
        $cacheName = 'ADS_POSITION_'.$positionKey;
        $cache = Cache::get($cacheName);
        if($cache === false){
            $adPosition = AdPosition::findOne(['key'=>$positionKey]);
            if($adPosition){
                $cache = self::find()->where(['positionID'=>$adPosition['id']])->orderBy('sort')->asArray()->all();
            }
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}

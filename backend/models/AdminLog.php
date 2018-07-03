<?php

namespace backend\models;

/**
 * This is the model class for table "{{%admin_log}}".
 *
 * @property integer $id
 * @property integer $adminID
 * @property integer $createTime
 * @property string $createIP
 * @property string $action
 * @property string $controller
 * @property string $param
 */
class AdminLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adminID', 'createIP'], 'required'],
            [['adminID', 'createTime'], 'integer'],
            [['createIP'], 'string', 'max' => 15],
            [['action', 'controller'], 'string', 'max' => 50],
            [['param'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '日志ID',
            'adminID' => '用户ID',
            'createTime' => '操作时间',
            'createIP' => '操作IP',
            'action' => '动作',
            'controller' => '控制器',
            'param' => '参数',
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
}

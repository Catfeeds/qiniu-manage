<?php

namespace backend\models;

/**
 * This is the model class for table "{{%admin_login_log}}".
 *
 * @property integer $id
 * @property integer $adminID
 * @property integer $loginTime
 * @property string $loginIP
 * @property string $loginArea
 */
class AdminLoginLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adminID', 'loginTime', 'loginArea'], 'required'],
            [['adminID', 'loginTime'], 'integer'],
            [['loginIP'], 'string', 'max' => 15],
            [['loginArea'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '日志ID',
            'adminID' => '管理员ID',
            'loginTime' => '登录时间',
            'loginIP' => '登录IP',
            'loginArea' => '登录地区',
        ];
    }

}

<?php

namespace backend\models;

/**
 * This is the model class for table "{{%admin_role}}".
 *
 * @property integer $adminID
 * @property integer $roleID
 */
class AdminRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adminID', 'roleID'], 'required'],
            [['adminID', 'roleID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adminID' => '管理员ID',
            'roleID' => '角色ID',
        ];
    }

}

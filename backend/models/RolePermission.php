<?php

namespace backend\models;

/**
 * This is the model class for table "{{%role_permission}}".
 *
 * @property integer $permissionID
 * @property integer $roleID
 */
class RolePermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleID', 'permissionID'], 'required'],
            [['roleID', 'permissionID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'roleID' => '角色ID',
            'permissionID' => '权限ID',
        ];
    }

}

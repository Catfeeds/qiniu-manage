<?php

namespace backend\models;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $realName
 * @property string $phone
 * @property string $email
 * @property string $encrypt
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $status
 *
 * @property Role[] $roles
 */
class Admin extends \yii\db\ActiveRecord
{

    public static $labelStatus = ['停用', '启用'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->status = 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'encrypt'], 'required'],
            [['createTime', 'updateTime'], 'integer'],
            [['username'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 64],
            [['realName'], 'string', 'max' => 40],
            [['phone'], 'string', 'max' => 11],
            [['email'], 'string', 'max' => 100],
            [['encrypt'], 'string', 'max' => 6],
            [['status'], 'integer', 'max' => 1],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '管理员ID',
            'username' => '用户名',
            'password' => '密码',
            'realName' => '真实姓名',
            'phone' => '电话号码',
            'email' => '电子邮件',
            'encrypt' => '加密字符',
            'status' => '状态',
            'roles' => '角色',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
    }

    /**
     * 校验前处理数据
     *
     * @return bool
     */
    public function beforeValidate()
    {
        $this->status === 'on' && $this->status = 1;
        ($this->status === 'off' || is_null($this->status) ) && $this->status = 0;
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

    /**
     * 管理员- 角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdminRoles(){
        return $this->hasMany(AdminRole::class, ['adminID'=>'id']);
    }

    /**
     * 管理员角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoles(){
        return $this->hasMany(Role::class, ['id'=>'roleID'])->via('adminRoles');
    }
}

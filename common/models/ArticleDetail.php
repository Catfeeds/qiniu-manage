<?php

namespace common\models;

/**
 * This is the model class for table "{{%article_detail}}".
 *
 * @property integer $articleID
 * @property string $content
 *
 * @property Article $article
 */
class ArticleDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['articleID'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['articleID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'articleID' => '文章ID',
            'content' => '分类内容',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'articleID']);
    }
}

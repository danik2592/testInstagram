<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_media".
 *
 * @property int $id
 * @property string|null $url
 * @property int $post_id
 *
 * @property InstaPosts $post
 */
class PostMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['post_id'], 'required'],
            [['post_id'], 'default', 'value' => null],
            [['post_id'], 'integer'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstaPosts::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'post_id' => 'Post ID',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery|InstaPostsQuery
     */
    public function getPost()
    {
        return $this->hasOne(InstaPosts::className(), ['id' => 'post_id']);
    }

    /**
     * {@inheritdoc}
     * @return PostMediaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostMediaQuery(get_called_class());
    }
}

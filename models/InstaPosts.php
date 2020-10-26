<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insta_posts".
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $type
 * @property string|null $shortCode
 * @property int|null $timestamp
 * @property int $user_id
 *
 * @property PostMedia[] $postMedia
 * @property InstaUsers $user
 */
class InstaPosts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insta_posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['timestamp', 'user_id'], 'default', 'value' => null],
            [['timestamp', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['type'], 'string', 'max' => 50],
            [['shortCode'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstaUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'type' => 'Type',
            'shortCode' => 'Short Code',
            'timestamp' => 'Timestamp',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[PostMedia]].
     *
     * @return \yii\db\ActiveQuery|PostMediaQuery
     */
    public function getPostMedia()
    {
        return $this->hasMany(PostMedia::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|InstaUsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstaUsers::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return InstaPostsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstaPostsQuery(get_called_class());
    }
}

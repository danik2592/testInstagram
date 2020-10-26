<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insta_users".
 *
 * @property int $id
 * @property string $username
 * @property string|null $imageUrl
 *
 * @property InstaPosts[] $instaPosts
 */
class InstaUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insta_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['imageUrl'], 'string'],
            [['username'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'imageUrl' => 'Image Url',
        ];
    }

    /**
     * Gets query for [[InstaPosts]].
     *
     * @return \yii\db\ActiveQuery|InstaPostsQuery
     */
    public function getInstaPosts()
    {
        return $this->hasMany(InstaPosts::className(), ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return InstaUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstaUsersQuery(get_called_class());
    }
}

<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InstaPosts]].
 *
 * @see InstaPosts
 */
class InstaPostsQuery extends \yii\db\ActiveQuery
{
    public function byCode($code)
    {
        return $this->andWhere(['shortCode' => $code]);
    }

    /**
     * {@inheritdoc}
     * @return InstaPosts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InstaPosts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

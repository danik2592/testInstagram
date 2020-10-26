<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PostMedia]].
 *
 * @see PostMedia
 */
class PostMediaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PostMedia[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PostMedia|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

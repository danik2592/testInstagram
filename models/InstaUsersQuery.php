<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InstaUsers]].
 *
 * @see InstaUsers
 */
class InstaUsersQuery extends \yii\db\ActiveQuery
{


    /**
     * {@inheritdoc}
     * @return InstaUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InstaUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

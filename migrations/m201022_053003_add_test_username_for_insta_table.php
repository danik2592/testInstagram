<?php

use yii\db\Migration;

/**
 * Class m201022_053003_add_test_username_for_insta_table
 */
class m201022_053003_add_test_username_for_insta_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $usernames = ['appletownkz', 'manchesterunited', 'netflixru'];
        foreach ($usernames as $name) {
            $intaUsers = new \app\models\InstaUsers();
            $intaUsers->username = $name;
            $intaUsers->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201022_053003_add_test_username_for_insta_table cannot be reverted.\n";

        return false;
    }
    */
}

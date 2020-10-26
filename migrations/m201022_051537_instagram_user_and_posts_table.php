<?php

use yii\db\Migration;

/**
 * Class m201022_051537_instagram_user_and_posts_table
 */
class m201022_051537_instagram_user_and_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('insta_users',[
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'imageUrl' => $this->text()
        ]);
        $this->createTable('insta_posts', [
            'id' => $this->primaryKey(),
            'text' => $this->text(),
            'type' => $this->string(50),
            'shortCode' => $this->string(100),
            'timestamp' => $this->integer(),
            'user_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('fk-insta_users', 'insta_posts', 'user_id', 'insta_users', 'id');
        $this->createTable('post_media', [
            'id' => $this->primaryKey(),
            'url' => $this->text(),
            'post_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('fk-post_media', 'post_media', 'post_id', 'insta_posts', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-insta_users', 'insta_posts');
        $this->dropForeignKey('fk-post_media', 'post_media');
        $this->dropTable('insta_users');
        $this->dropTable('insta_posts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201022_051537_instagram_user_and_posts_table cannot be reverted.\n";

        return false;
    }
    */
}

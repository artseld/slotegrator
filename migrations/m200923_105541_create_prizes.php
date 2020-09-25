<?php

use app\models\UserPrize;
use yii\db\Migration;

/**
 * Class m200923_105541_create_prizes
 */
class m200923_105541_create_prizes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
 
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('prize', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'parameters' => $this->json(),
            'count' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('user_prize', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'prize_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'value' => $this->string(),
            'status' => $this->string()->notNull()->defaultValue(UserPrize::STATUS_NEW),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx_user_prize_user_id',
            'user_prize',
            'user_id'
        );

        $this->addForeignKey(
            'fk_user_prize_user_id',
            'user_prize',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx_user_prize_prize_id',
            'user_prize',
            'prize_id'
        );

        $this->addForeignKey(
            'fk_user_prize_prize_id',
            'user_prize',
            'prize_id',
            'prize',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_user_prize_prize_id',
            'user_prize'
        );

        $this->dropIndex(
            'idx_user_prize_prize_id',
            'user_prize'
        );

        $this->dropForeignKey(
            'fk_user_prize_user_id',
            'user_prize'
        );

        $this->dropIndex(
            'idx_user_prize_user_id',
            'user_prize'
        );

        $this->dropTable('user_award');
        $this->dropTable('award');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200923_105541_create_prizes cannot be reverted.\n";

        return false;
    }
    */
}

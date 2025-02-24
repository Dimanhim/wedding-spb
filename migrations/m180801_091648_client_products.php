<?php

use yii\db\Schema;
use yii\db\Migration;

class m180801_091648_client_products extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%client_products}}', [
            'id'                => Schema::TYPE_PK,
            'client_id'         => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%client_products}}');
    }
}

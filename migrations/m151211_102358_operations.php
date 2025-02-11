<?php

use yii\db\Schema;
use yii\db\Migration;

class m151211_102358_operations extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%operations}}', [
            'id'            => Schema::TYPE_PK,
            'name'          => Schema::TYPE_STRING . ' NOT NULL',
            'user_id'       => Schema::TYPE_INTEGER,
            'type_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'cat_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'payment_type'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'total_price'   => Schema::TYPE_FLOAT . ' NOT NULL',
            'purchase_price'   => Schema::TYPE_FLOAT . ' NOT NULL',
            'repeated'      => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'interval'      => Schema::TYPE_STRING,
            'months'        => Schema::TYPE_STRING,
            'days'          => Schema::TYPE_STRING,
            'week'          => Schema::TYPE_STRING,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%operations}}');
    }
}

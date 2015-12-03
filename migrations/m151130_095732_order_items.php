<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_095732_order_items extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order_items}}', [
            'id'                => Schema::TYPE_PK,
            'order_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'size_id'           => Schema::TYPE_INTEGER,
            'amount'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'price'             => Schema::TYPE_FLOAT . ' NOT NULL',
            'delivery_status'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'arrived'           => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%order_items}}');
    }
}

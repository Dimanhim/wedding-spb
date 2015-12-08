<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_210400_receipts extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%receipts}}', [
            'id'                => Schema::TYPE_PK,
            'payment_type'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'total_amount'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'price'             => Schema::TYPE_FLOAT . ' NOT NULL',
            'sale'              => Schema::TYPE_FLOAT . ' NOT NULL',
            'total_price'       => Schema::TYPE_FLOAT . ' NOT NULL',
            'change'            => Schema::TYPE_FLOAT,
            'manager_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%receipts}}');
    }
}

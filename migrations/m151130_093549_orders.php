<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_093549_orders extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orders}}', [
            'id'                => Schema::TYPE_PK,
            'await_date'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'payment_type'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'total_payed'       => Schema::TYPE_FLOAT,
            'total_rest'        => Schema::TYPE_FLOAT,
            //'supplier'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'total_amount'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'total_price'       => Schema::TYPE_FLOAT . ' NOT NULL',
            'payment_status'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'delivery_status'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%orders}}');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_210405_receipt_items extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%receipt_items}}', [
            'id'                => Schema::TYPE_PK,
            'receipt_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'size_id'           => Schema::TYPE_INTEGER,
            'amount'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'price'             => Schema::TYPE_FLOAT . ' NOT NULL',
            'sale'              => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'total_price'       => Schema::TYPE_FLOAT . ' NOT NULL',
            'gift'              => Schema::TYPE_BOOLEAN,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%receipt_items}}');
    }
}

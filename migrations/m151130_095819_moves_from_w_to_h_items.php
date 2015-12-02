<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_095819_moves_from_w_to_h_items extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%w_h_moves_items}}', [
            'id'            => Schema::TYPE_PK,
            'move_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'size_id'       => Schema::TYPE_INTEGER,
            'amount'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'status'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'arrived'       => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%w_h_moves_items}}');
    }
}

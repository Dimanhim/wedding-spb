<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_095830_moves_from_h_to_w extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%h_w_moves}}', [
            'id'            => Schema::TYPE_PK,
            'total_amount'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'status'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%h_w_moves}}');
    }
}

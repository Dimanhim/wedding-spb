<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_140501_amounts extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%amounts}}', [
            'id'            => Schema::TYPE_PK,
            'product_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'amount_type'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'size_id'       => Schema::TYPE_INTEGER,
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%amounts}}');
    }
}

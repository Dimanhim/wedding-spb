<?php

use yii\db\Schema;
use yii\db\Migration;

class m161125_114821_product_fashions extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_fashions}}', [
            'id'                => Schema::TYPE_PK,
            'product_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'fashion_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%product_fashions}}');
    }
}

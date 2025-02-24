<?php

use yii\db\Schema;
use yii\db\Migration;

class m180912_123217_blacklist extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%blacklist}}', [
            'id'            => Schema::TYPE_PK,
            'ip'            => Schema::TYPE_STRING . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%blacklist}}');
    }
}

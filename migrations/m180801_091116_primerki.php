<?php

use yii\db\Schema;
use yii\db\Migration;

class m180801_091116_primerki extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%primerki}}', [
            'id'                => Schema::TYPE_PK,
            'manager_id'        => Schema::TYPE_INTEGER,
            'client_id'         => Schema::TYPE_INTEGER . ' NOT NULL',
            'date'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'description'       => Schema::TYPE_TEXT,
            'result'            => Schema::TYPE_SMALLINT,
            'receipt_id'        => Schema::TYPE_INTEGER,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%primerki}}');
    }
}

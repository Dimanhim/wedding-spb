<?php

use yii\db\Schema;
use yii\db\Migration;

class m180801_091642_primerka_wishes extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%primerka_wishes}}', [
            'id'                => Schema::TYPE_PK,
            'primerka_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%primerka_wishes}}');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m161227_094320_articles extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%articles}}', [
            'id'            => Schema::TYPE_PK,
            'name'          => Schema::TYPE_STRING . ' NOT NULL',
            'category_id'   => Schema::TYPE_INTEGER,
            'image'         => Schema::TYPE_STRING,
            'introtext'     => Schema::TYPE_TEXT,
            'content'       => Schema::TYPE_TEXT,
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%articles}}');
    }
}

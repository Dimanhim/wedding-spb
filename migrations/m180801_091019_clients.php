<?php

use yii\db\Schema;
use yii\db\Migration;

class m180801_091019_clients extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%clients}}', [
            'id'                => Schema::TYPE_PK,
            'manager_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'fio'               => Schema::TYPE_STRING . ' NOT NULL',
            'phone'             => Schema::TYPE_STRING . ' NOT NULL',
            'email'             => Schema::TYPE_STRING,
            'first_visit'       => Schema::TYPE_INTEGER,
            'visit_purpose'     => Schema::TYPE_STRING,
            'is_appoint'        => Schema::TYPE_BOOLEAN,
            'birtday'           => Schema::TYPE_INTEGER,
            'event_date'        => Schema::TYPE_INTEGER,
            'sizes'             => Schema::TYPE_STRING,
            'wedding_place'     => Schema::TYPE_STRING,
            'source'            => Schema::TYPE_SMALLINT,
            'description'       => Schema::TYPE_TEXT,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%clients}}');
    }
}

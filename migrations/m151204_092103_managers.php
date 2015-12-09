<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_092103_managers extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%managers}}', [
            'id'                => Schema::TYPE_PK,
            'user_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
            'name'              => Schema::TYPE_STRING . ' NOT NULL',
            'surname'           => Schema::TYPE_STRING . ' NOT NULL',
            'fathername'        => Schema::TYPE_STRING,
            'employment_date'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'vacation_start'    => Schema::TYPE_INTEGER,
            'vacation_end'      => Schema::TYPE_INTEGER,
            'advance_date'      => Schema::TYPE_INTEGER,
            'salary_date'       => Schema::TYPE_INTEGER,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%managers}}');
    }
}

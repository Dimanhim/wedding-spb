<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_143440_users extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id'        => Schema::TYPE_PK,
            'username'  => Schema::TYPE_STRING . ' NOT NULL',
            'email'     => Schema::TYPE_STRING,

            'auth_key'              => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash'         => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token'  => Schema::TYPE_STRING,
            'email_confirm_token'   => Schema::TYPE_STRING,
            
            'role' => Schema::TYPE_STRING . ' NOT NULL',

            'status'     => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('email_index_unique', '{{%users}}', 'email', true);
        $this->createIndex('username_index_unique', '{{%users}}', 'username', true);

    }

    public function down() {
        $this->dropTable('{{%users}}');
    }
}

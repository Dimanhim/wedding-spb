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
            'email'     => Schema::TYPE_STRING . ' NOT NULL',
            'name'      => Schema::TYPE_STRING . ' NOT NULL',
            'surname'           => Schema::TYPE_STRING . ' NOT NULL',
            'fathername'        => Schema::TYPE_STRING,
            'employment_date'   => Schema::TYPE_STRING . ' NOT NULL',

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

        $this->insert('{{%users}}', [
            'username'  => 'admin',
            'email'     => 'info@madeinmed.ru',
            'name'      => 'Администратор',
            'surname'   => 'Администратор',

            'auth_key'      => 'D08uUhskO3frc5t1f1G2TUOK8fQxOQ3t',
            'password_hash' => '$2y$13$KM5JdtkTlSc3aTZouDRuXevcowCQs0EljhxI2DXgeHpnzTIpVxciG', //123456
            
            'role' => 'admin',
            
            'status'     => 10,
            'created_at' => time(),
            'updated_at' => time()
        ]);

    }

    public function down() {
        $this->dropTable('{{%users}}');
    }
}

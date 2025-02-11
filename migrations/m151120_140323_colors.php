<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_140323_colors extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%colors}}', [
            'id'            => Schema::TYPE_PK,
            'category_id'   => Schema::TYPE_INTEGER,
            'site_color_id' => Schema::TYPE_INTEGER,
            'name'          => Schema::TYPE_STRING . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%colors}}');
    }
}

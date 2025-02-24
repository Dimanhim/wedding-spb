<?php

use yii\db\Schema;
use yii\db\Migration;

class m171108_112657_static_pages extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%static_pages}}', [
            'id'            => Schema::TYPE_PK,
            'name'          => Schema::TYPE_STRING . ' NOT NULL',
            'filter_name'   => Schema::TYPE_STRING,
            'alias'         => Schema::TYPE_STRING . ' NOT NULL',
            'h1'            => Schema::TYPE_STRING,
            'title'         => Schema::TYPE_STRING,
            'keywords'      => Schema::TYPE_STRING,
            'description'   => Schema::TYPE_STRING,
            'content'       => Schema::TYPE_TEXT,
            'category_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'parent_id'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'fashion_id'    => Schema::TYPE_INTEGER,
            'feature_id'    => Schema::TYPE_INTEGER,
            'occasion_id'   => Schema::TYPE_INTEGER,
            'color_id'      => Schema::TYPE_INTEGER,
            'price_cat_id'  => Schema::TYPE_INTEGER,
            'min_price'     => Schema::TYPE_INTEGER,
            'max_price'     => Schema::TYPE_INTEGER,
            'is_deleted'    => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%static_pages}}');
    }
}

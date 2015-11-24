<?php

use yii\db\Schema;
use yii\db\Migration;

class m151118_161409_products extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%products}}', [
            'id'            => Schema::TYPE_PK,
            'category_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'marka'         => Schema::TYPE_STRING,
            'model'         => Schema::TYPE_STRING,
            'color'         => Schema::TYPE_STRING,
            'description'   => Schema::TYPE_STRING,
            'photo'         => Schema::TYPE_STRING,
            'purchase_price_small'      => Schema::TYPE_FLOAT . ' NOT NULL',
            'purchase_price_big'        => Schema::TYPE_FLOAT . ' NOT NULL',
            'purchase_price_small_dol'  => Schema::TYPE_FLOAT,
            'purchase_price_big_dol'    => Schema::TYPE_FLOAT,
            'recommended_price_small'   => Schema::TYPE_FLOAT . ' NOT NULL',
            'recommended_price_big'     => Schema::TYPE_FLOAT . ' NOT NULL',
            'price_small'   => Schema::TYPE_FLOAT . ' NOT NULL',
            'price_big'     => Schema::TYPE_FLOAT . ' NOT NULL',
            'ratio'   => Schema::TYPE_FLOAT . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%products}}');
    }
}

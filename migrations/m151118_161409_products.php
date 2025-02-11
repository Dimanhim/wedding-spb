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
            'name'          => Schema::TYPE_STRING,
            'category_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'marka_id'      => Schema::TYPE_INTEGER,
            'model_id'      => Schema::TYPE_INTEGER,
            'color_id'      => Schema::TYPE_INTEGER,
            'ratio_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'description'   => Schema::TYPE_STRING,
            'photo'         => Schema::TYPE_STRING,
            'photo2'         => Schema::TYPE_STRING,
            'photo3'         => Schema::TYPE_STRING,
            'purchase_price'            => Schema::TYPE_FLOAT,
            'purchase_price_small'      => Schema::TYPE_FLOAT,
            'purchase_price_big'        => Schema::TYPE_FLOAT,
            'purchase_price_dol'        => Schema::TYPE_FLOAT,
            'purchase_price_small_dol'  => Schema::TYPE_FLOAT,
            'purchase_price_big_dol'    => Schema::TYPE_FLOAT,
            'recommended_price'         => Schema::TYPE_FLOAT,
            'recommended_price_small'   => Schema::TYPE_FLOAT,
            'recommended_price_big'     => Schema::TYPE_FLOAT,
            'price'         => Schema::TYPE_FLOAT,
            'price_small'   => Schema::TYPE_FLOAT,
            'price_big'     => Schema::TYPE_FLOAT,
            'purchase_date' => Schema::TYPE_INTEGER,
            'sell_date'     => Schema::TYPE_INTEGER,
            'is_deleted'    => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

    }

    public function down() {
        $this->dropTable('{{%products}}');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_135637_categories extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%categories}}', [
            'id'            => Schema::TYPE_PK,
            'name'          => Schema::TYPE_STRING . ' NOT NULL',
            'type'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $time = time();
        $this->insert('{{%categories}}', ['name' => 'Свадебные платья', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Вечерние платья', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Фата', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Болеро', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Шубки-накидки', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Обувь', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Пояса', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Украшение', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Подвязки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Заколки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Перчатки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Чулки-колготки', 'type' => 3, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Бокалы', 'type' => 4, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Аксессуары для свадеб', 'type' => 4, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Кринолины', 'type' => 5, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Чехлы-сумки с логотипом', 'type' => 6, 'created_at' => $time, 'updated_at' => $time]);

    }

    public function down() {
        $this->dropTable('{{%categories}}');
    }
}

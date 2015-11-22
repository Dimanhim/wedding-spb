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
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $time = time();
        $this->insert('{{%categories}}', ['name' => 'Свадебные платья', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Вечерние платья', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Фата', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Болеро', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Шубки-накидки', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Обувь', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Пояса', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Украшение', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Подвязки', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Заколки', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Перчатки', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Чулки-колготки', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Бокалы', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Аксессуары для свадеб', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Кринолины', 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['name' => 'Чехлы-сумки с логотипом', 'created_at' => $time, 'updated_at' => $time]);

    }

    public function down() {
        $this->dropTable('{{%categories}}');
    }
}

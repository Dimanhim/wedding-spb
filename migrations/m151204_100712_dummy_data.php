<?php

use yii\db\Schema;
use yii\db\Migration;

use app\models\Product;
use app\models\Amount;

class m151204_100712_dummy_data extends Migration
{
    public function up()
    {
        $time = time();

        //Создаём админа
        $this->insert('{{%users}}', [
            'id' => 1,
            'username'  => 'admin',
            'email'     => 'admin@madeinmed.ru',
            'auth_key'      => 'D08uUhskO3frc5t1f1G2TUOK8fQxOQ3t',
            'password_hash' => '$2y$13$KM5JdtkTlSc3aTZouDRuXevcowCQs0EljhxI2DXgeHpnzTIpVxciG', //123456
            'role' => 'admin',
            'status'     => 10,
            'created_at' => $time,
            'updated_at' => $time
        ]);

        //Генерим менеджеров
        for ($i = 2; $i <= 6; $i++) { 
            $this->insert('{{%users}}', [
                'id' => $i,
                'username'  => 'manager'.$i,
                'email'     => 'manager'.$i.'@madeinmed.ru',
                'auth_key'      => 'D08uUhskO3frc5t1f1G2TUOK8fQxOQ3t',
                'password_hash' => '$2y$13$KM5JdtkTlSc3aTZouDRuXevcowCQs0EljhxI2DXgeHpnzTIpVxciG', //123456
                'role' => 'manager',
                'status'     => 10,
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $manager_id = $i - 1;
            $this->insert('{{%managers}}', [
                'id' => $manager_id,
                'user_id'  => $i,
                'name'     => 'Продавец #'.$manager_id,
                'surname'      => 'Просто',
                'employment_date' => $time,
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }

        //Генерим марки, модели, цвета, размеры и коэффициенты
        for ($i = 1; $i <= 5; $i++) {
            $this->insert('{{%marks}}', [
                'id' => $i,
                'name'  => 'Марка №'.$i,
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $this->insert('{{%models}}', [
                'id' => $i,
                'name'  => 'Модель №'.$i,
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $this->insert('{{%colors}}', [
                'id' => $i,
                'name'  => 'Цвет №'.$i,
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $this->insert('{{%sizes}}', [
                'id' => $i,
                'name'  => ($i*3 + 40),
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $this->insert('{{%rates}}', [
                'id' => $i,
                'name'  => ($i / 2),
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }

        //Генерим категории
        $this->insert('{{%categories}}', ['id' => 1, 'name' => 'Свадебные платья', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 2, 'name' => 'Вечерние платья', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 3, 'name' => 'Фата', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 4, 'name' => 'Болеро', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 5, 'name' => 'Шубки-накидки', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 6, 'name' => 'Обувь', 'type' => 1, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 7, 'name' => 'Пояса', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 8, 'name' => 'Украшение', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 9, 'name' => 'Подвязки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 10, 'name' => 'Заколки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 11, 'name' => 'Перчатки', 'type' => 2, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 12, 'name' => 'Чулки-колготки', 'type' => 3, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 13, 'name' => 'Бокалы', 'type' => 4, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 14, 'name' => 'Аксессуары для свадеб', 'type' => 4, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 15, 'name' => 'Кринолины', 'type' => 5, 'created_at' => $time, 'updated_at' => $time]);
        $this->insert('{{%categories}}', ['id' => 16, 'name' => 'Чехлы-сумки с логотипом', 'type' => 6, 'created_at' => $time, 'updated_at' => $time]);

        //Генерим товары
        for ($i = 1; $i <= 100; $i++) {
            $category_id = rand(1, 16);
            $price = rand(1000, 50000);
            $image = rand(1, 4);

            $this->insert('{{%products}}', [
                'id' => $i,
                'category_id'  => $category_id,
                'marka_id'     => rand(1, 5),
                'model_id'     => rand(1, 5),
                'color_id'     => rand(1, 5),
                'photo' => '/files/'.$image.'.jpeg',
                'purchase_price_small' => $price,
                'purchase_price_big' => $price,
                'purchase_price_small_dol' => $price,
                'purchase_price_big_dol' => $price,
                'recommended_price_small' => $price,
                'recommended_price_big' => $price,
                'price_small' => $price,
                'price_big' => $price,
                'ratio_id' => rand(1, 5),
                'created_at' => $time,
                'updated_at' => $time
            ]);

            $sizes = rand(1, 4);
            for ($j = 1; $j <= $sizes; $j++) {
                $size = rand(1, 5);
                for ($k = 0; $k < 3; $k++) { 
                    $this->insert('{{%amounts}}', [
                        'product_id'  => $i,
                        'amount_type' => $k,
                        'amount' => rand(0, 10),
                        'size_id' => $size,
                        'created_at' => $time,
                        'updated_at' => $time
                    ]);
                }
            }
        }

        //Генерим продажи
        for ($i = 1; $i <= 100; $i++) { 
            $receipt_items = [rand(1, 100), rand(1, 100), rand(1, 100)];
            $total_amount = 0;
            $total_price = 0;
            foreach ($receipt_items as $product_id) {
                $product = Product::findOne($product_id);
                $amount_obj = Amount::find()->where(['product_id' => $product_id])->orderBy('RAND()')->one();
                $amount = rand(1, 3);

                $total_amount += $amount;
                $total_price += $amount * $product->price_small;

                $this->insert('{{%receipt_items}}', [
                    'receipt_id'   => $i,
                    'product_id'   => $product_id,
                    'size_id'      => $amount_obj->size_id,
                    'amount'       => $amount,
                    'price'        => $product->price_small,
                    'sale'         => 0,
                    'total_price'  => $amount * $product->price_small,
                    'gift'         => 0,
                    'created_at' => $time,
                    'updated_at' => $time
                ]);
            }

            $this->insert('{{%receipts}}', [
                'id' => $i,
                'payment_type'  => rand(0, 1),
                'total_amount'  => $total_amount,
                'price'         => $total_price,
                'sale'          => 0,
                'total_price'   => $total_price,
                'change'        => 0,
                'manager_id'    => rand(1, 5),
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }

    }

    public function down()
    {
        echo "m151204_100712_dummy_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

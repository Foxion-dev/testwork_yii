<?php

namespace frontend\controllers;

use app\models\Item;
use Faker\Factory;
use Yii;

class ItemController extends \yii\web\Controller
{
    public function actionDatabase()
    {
        $start_time = microtime(true);

        $subQuery = (new \yii\db\Query())->select('MAX(currency.date)')->from('currency');
        $rows = (new \yii\db\Query())
            ->select(['items.name', 'items.category', 'items.price', "(items.price * currency.value) AS priceRUB", "currency.date AS dateCurrency" ])
            ->from('items')
            ->join('INNER JOIN', 'currency', 'items.currency = currency.currency')
            ->where(['items.category' => 3])
            ->andWhere(['=', 'currency.date', $subQuery])
            ->limit(10)
            ->all();

        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time)/60;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'time' => $execution_time, // Yii::getLogger()->getElapsedTime()
            'result ' => $rows,
        ];

    }
    public function actionArray()
    {
        $start_time = microtime(true);
        $rows = [];
        $currencies = [];

        $items = (new \yii\db\Query())
            ->select(['name', 'category', 'price', 'currency'])
            ->from('items')
            ->where(['items.category' => 3])
            ->limit(10)
            ->all();

        $actualCurrencies = (new \yii\db\Query())
            ->select(['currency', 'value', 'date'])
            ->from('currency')
            ->where(['<', 'date', date('Y-m-d')])
            ->orderBy(['date' => SORT_DESC])
            ->limit(2)
            ->all();

        foreach ($actualCurrencies as $item) {
            $currencies[$item['currency']] = $item;
        }

        foreach ($items as $item) {

            $rows[] = [
              'name' => $item['name'],
              'category' => $item['category'],
              'price' => $item['price'],
              'priceRUB' => $currencies[$item['currency']]['value'] * $item['price'],
              'dateСurrency' => $currencies[$item['currency']]['date'],
            ];
        }
        $end_time = microtime(true);

        $execution_time = ($end_time - $start_time)/60;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'time' => $execution_time, // Yii::getLogger()->getElapsedTime()
            'result ' => $rows,
        ];
    }
    public function actionGenerate()
    {
        set_time_limit(-1);

        $faker = Factory::create();

        $currencies = ['USD', 'EUR'];

        for($i = 0; $i < 400000; $i++)

        {
            $post = new Item();
            $post->name = $faker->text(rand(10, 30));
            $post->category = rand(1, 10);
            $post->price = rand(1, 10000);
            $post->currency = $currencies[rand(0, 1)];

            $post->save(false);

        }
    }

}

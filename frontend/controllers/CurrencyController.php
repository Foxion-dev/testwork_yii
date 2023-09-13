<?php

namespace frontend\controllers;

use app\models\Currency;
use DateInterval;
use DatePeriod;
use DateTime;

class CurrencyController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionGenerate()
    {
        set_time_limit(-1);

        $start = new DateTime("01.01.2022");
        $end = new DateTime("01.07.2023");
        $step = new DateInterval('P1D');
        $period = new DatePeriod($start, $step, $end);

        foreach($period as $datetime) {

            $currencyUSD = new Currency();

            $currencyUSD->date = $datetime->format('Y-m-d');
            $currencyUSD->value = rand(10, 100);
            $currencyUSD->currency = 'USD';
            $currencyUSD->save(false);

            $currencyEUR = new Currency();

            $currencyEUR->date = $datetime->format('Y-m-d');
            $currencyEUR->value = rand(10, 100);
            $currencyEUR->currency = 'EUR';
            $currencyEUR->save(false);
        }
    }
}

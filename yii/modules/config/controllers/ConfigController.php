<?php

namespace app\modules\config\controllers;

use yii\web\Controller;

class ConfigController extends Controller
{

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionConfiguration(): string
    {
        return $this->render('configuration');
    }

}

<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;

use yii\web\{
    Controller,
    Response
};

class LoginController extends Controller
{
    public function actionLogin(): Response|string
    {
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}

<?php

namespace app\controllers;

use app\services\UserService;
use Yii;

use app\models\{
    LoginForm,
    SignupForm
};
use yii\web\{
    Controller,
    Response
};

class LoginController extends Controller
{

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


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

    public function actionSignup(): Response|string
    {
        $model = new SignupForm(new UserService());

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'Registration successful! You can now log in.');
                return $this->redirect(['login/login']);
            }
        }

        return $this->render('signup', ['model' => $model]);
    }

}

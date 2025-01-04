<?php /** @noinspection PhpUnused */

namespace app\controllers;

use app\models\ContactForm;
use Yii;

use yii\filters\{
    AccessControl,
    VerbFilter
};
use yii\web\{
    Controller,
    Response
};

class SiteController extends Controller
{

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
        try {
            Yii::$app->db->createCommand('SELECT 1')->execute();
            return $this->render('index');
        } catch (\Exception $e) {
            Yii::error('Database connection failed: ' . $e->getMessage(), __METHOD__);
            $this->layout = 'fatal';
            return $this->render('error', [
                'name' => 'Database Connection Error',
                'message' => 'Unable to connect to the database. Please try again later.',
            ]);
        }
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact(): Response|string
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout(): string
    {
        return $this->render('about');
    }

    public function actionConfiguration(): string
    {
        return $this->render('configuration');
    }

    public function actionFeatures(): string
    {
        return $this->render('features');
    }

    public function actionStockAnalyis(): string
    {
        return $this->render('stock-analyis');
    }

    public function actionPortfolioManagement(): string
    {
        return $this->render('portfolio-management');
    }

}

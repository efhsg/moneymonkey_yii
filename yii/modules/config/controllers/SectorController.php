<?php

namespace app\modules\config\controllers;

use app\modules\config\models\Sector;
use app\modules\config\models\SectorSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\{Controller, NotFoundHttpException, Response};

/**
 * SectorController implements the CRUD actions for Sector model.
 */
class SectorController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'delete-confirm'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Sector models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new SectorSearch();
        $userId = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $userId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): Response|string
    {
        $model = $this->findModel($id);

        $industriesDataProvider = new ActiveDataProvider([
            'query' => $model->getIndustries(),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('view', [
            'model' => $model,
            'industriesDataProvider' => $industriesDataProvider,
        ]);
    }

    /**
     * Finds the Sector model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Sector the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Sector
    {
        if (($model = Sector::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Sector model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {

        $userId = Yii::$app->user->id;
        $model = new Sector();
        $model->user_id = $userId;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sector model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate($id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): Response|string
    {
        if (!Yii::$app->request->post('confirm')) {
            return $this->actionDeleteConfirm($id);
        }

        $model = $this->findModel($id);
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', "Sector {$model->name} deleted successfully.");
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Unable to delete the sector. Please try again later.');
        }

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDeleteConfirm($id): string
    {
        $model = $this->findModel($id);

        $industries = $model->getIndustries()->with('stocks')->all();

        return $this->render('delete-confirm', [
            'model' => $model,
            'industries' => $industries,
        ]);
    }
}

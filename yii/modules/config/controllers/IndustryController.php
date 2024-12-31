<?php /** @noinspection PhpUnused */

namespace app\modules\config\controllers;

use app\modules\config\models\Industry;
use app\modules\config\models\IndustrySearch;
use app\modules\config\models\Sector;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * IndustryController implements the CRUD actions for Industry model.
 */
class IndustryController extends Controller
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
     * Lists all Industry models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new IndustrySearch();
        $activeDataProvider = $searchModel->search($this->request->queryParams, Yii::$app->user->id);
        $activeDataProvider->pagination = false;

        $allModels = $activeDataProvider->getModels();

        $groupedModels = $this->groupBySector($allModels);

        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $groupedModels,
            'pagination' => [
                'pageSize' => 20,  // or whatever page size you want
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $arrayDataProvider,
        ]);
    }


    private function groupBySector(array $models): array
    {
        $grouped = [];
        $currentSectorId = null;

        foreach ($models as $model) {
            $sectorName = $model->sector ? $model->sector->name : 'Unknown Sector';
            $row = [
                'sector_name' => $currentSectorId !== $model->sector_id ? $sectorName : '',
                'industry_name' => $model->name,
                'id' => $model->id,
            ];
            $currentSectorId = $model->sector_id;
            $grouped[] = $row;
        }

        return $grouped;
    }

    /**
     * Displays a single Industry model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $stocksDataProvider = new ActiveDataProvider([
            'query' => $model->getStocks(),
        ]);

        return $this->render('view', [
            'model' => $model,
            'stocksDataProvider' => $stocksDataProvider,
        ]);
    }

    /**
     * Finds the Industry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Industry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Industry
    {
        if (($model = Industry::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Industry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Industry();
        $sectors = ArrayHelper::map(Sector::find()->all(), 'id', 'name');

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'sectors' => $sectors,
        ]);
    }

    /**
     * Updates an existing Industry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $sectors = ArrayHelper::map(Sector::find()->all(), 'id', 'name');

        return $this->render('update', [
            'model' => $model,
            'sectors' => $sectors,
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
            Yii::$app->session->setFlash('success', "Sector $model->name deleted successfully.");
        } catch (Throwable $e) {
            Yii::error($e->getMessage(), 'database');
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

        $stocks = $model->getStocks()->all();

        return $this->render('delete-confirm', [
            'model' => $model,
            'stocks' => $stocks,
        ]);
    }
}

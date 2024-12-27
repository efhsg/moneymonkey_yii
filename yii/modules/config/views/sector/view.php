<?php

use app\helpers\BreadcrumbHelper;
use app\modules\config\models\Sector;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var Sector $model */
/** @var ActiveDataProvider $industriesDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs('Sectors', 'index', $model, 'View');
YiiAsset::register($this);
?>
<div class="sector-view container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger me-2',
                'data' => [
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Sector Details</strong>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name', // Add any other sector-specific attributes you want to display
                ],
                'options' => ['class' => 'table table-striped table-hover mb-0'],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Industries in <?= Html::encode($model->name) ?></strong>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $industriesDataProvider,
                'summary' => '<strong>{begin}</strong> to <strong>{end}</strong> out of <strong>{totalCount}</strong>',
                'summaryOptions' => ['class' => 'text-start m-2'],
                'layout' => "{items}"
                    . "<div class='card-footer position-relative py-1 px-2'>"
                    . "<div class='position-absolute start-0 top-50 translate-middle-y'>{summary}</div>"
                    . "<div class='text-center'>{pager}</div>"
                    . "</div>",
                'tableOptions' => [
                    'class' => 'table table-striped table-hover mb-0',
                    'data-responsive' => 'true',
                    'aria-label' => 'Industry Table',
                ],
                'pager' => [
                    'options' => ['class' => 'pagination justify-content-center m-3'],
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                    'prevPageLabel' => 'Previous',
                    'nextPageLabel' => 'Next',
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',

                    'pageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'nextPageCssClass' => 'page-item',
                    'prevPageCssClass' => 'page-item',
                    'activePageCssClass' => 'active',
                    'disabledPageCssClass' => 'disabled',
                ],
                'columns' => [
                    'name', // Name of the industry
                    [
                        'attribute' => 'description', // Replace with relevant attributes of Industry
                        'label' => 'Description',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'controller' => 'industry', // Adjust to match your Industry controller
                        'template' => '{view} {update} {delete}',
                        'urlCreator' => function ($action, $industry, $key, $index, $column) {
                            return ['industry/' . $action, 'id' => $industry->id];
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

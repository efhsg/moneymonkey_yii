<?php

use app\modules\config\models\Sector;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\config\models\SectorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sectors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sector-index container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Sector', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Sector List</strong>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'summary'      => '<strong>{begin}</strong> to <strong>{end}</strong> out of <strong>{totalCount}</strong>',
                'summaryOptions' => ['class' => 'text-start m-2'],
                'layout' => "{items}\n<div class='card-footer position-relative'><div class='position-absolute start-0 top-50 translate-middle-y'>{summary}</div><div class='text-center'>{pager}</div></div>",
                'tableOptions' => [
                    'class' => 'table table-striped table-hover mb-0',
                    'data-responsive' => 'true',
                    'aria-label' => 'Sector Table',
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
                'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'onclick' => 'window.location.href = "'
                            . Url::to(['view', 'id' => $model->id]) . '";',
                        'style' => 'cursor: pointer;',
                    ];
                },
                'columns' => [
                    'name',
                    [
                        'attribute' => 'industries_count',
                        'label' => 'Industries',
                        'value' => function ($model) {
                            return $model->industriesCount;
                        },
                        'filter' => false,
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Sector $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'template' => '{view} {update} {delete}',
                        'buttonOptions' => ['data-confirm' => false],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

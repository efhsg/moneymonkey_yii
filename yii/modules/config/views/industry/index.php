<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\helpers\BreadcrumbHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\config\models\IndustrySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Industries';
$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Configuration', 'url' => null],
    ],
    null,
    $this->title
);
?>
<div class="industry-index container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Industry', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Industry List</strong>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '<strong>{begin}</strong> to <strong>{end}</strong> out of <strong>{totalCount}</strong>',
                'summaryOptions' => ['class' => 'text-start m-2'],
                'layout' => "{items}"
                    . "<div class='card-footer position-relative py-3 px-2'>"
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
                'rowOptions' => function ($model) {
                    return [
                        'onclick' => 'window.location.href = "'
                            . Url::to(['view', 'id' => $model['id']]) . '";',
                        'style' => 'cursor: pointer;',
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'sector_name',
                        'label' => 'Sector Name',
                    ],
                    [
                        'attribute' => 'industry_name',
                        'label' => 'Industry Name',
                    ],
                    [
                        'class' => yii\grid\ActionColumn::class,
                        'urlCreator' => function ($action, $model) {
                            return Url::toRoute([$action, 'id' => $model['id']]);
                        },
                        'template' => '{update} {delete}',
                        'buttonOptions' => ['data-confirm' => false],
//                        'visibleButtons' => [
//                            'update' => fn($model) => !empty($model['id']),
//                            'delete' => fn($model) => !empty($model['id']),
//                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

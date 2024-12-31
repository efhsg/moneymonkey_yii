<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\helpers\BreadcrumbHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\config\models\Industry $model */
/** @var ActiveDataProvider $stocksDataProvider */

$this->title = $model->name;

$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Configuration', 'url' => null],
        ['label' => 'Industries', 'url' => ['index']],
    ],
    $model,
    'View'
);

YiiAsset::register($this);
?>
<div class="industry-view container py-4">

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
            <strong>Industry Details</strong>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Sector',
                        'value' => function ($model) {
                            return $model->sector->name ?? 'N/A';
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'label' => 'Industry',
                    ],
                ],
                'options' => ['class' => 'table table-striped table-hover mb-0'],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Stocks</strong>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $stocksDataProvider,
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
                    'aria-label' => 'Stock Table',
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
                        'onclick' => "window.location.href = '" . Url::to(['stock/view', 'id' => $model->id]) . "'",
                        'style' => 'cursor: pointer;',
                    ];
                },
                'columns' => [
                    'company_name',
                    'ticker',
                ],
            ]) ?>
        </div>
    </div>
</div>

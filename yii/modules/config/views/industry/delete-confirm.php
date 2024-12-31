<?php

use app\helpers\BreadcrumbHelper;
use app\models\Stock;
use yii\helpers\Html;

/** @var app\modules\config\models\Industry $model */
/** @var app\modules\config\models\Industry[] $stocks */

$this->title = 'Confirm Delete Industry';
$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Configuration', 'url' => null],
        ['label' => 'Stocks', 'url' => ['index']],
    ],
    $model,
    'Confirm Delete'
);


?>
<div class="industry-delete-confirm container py-4">

    <div class="card">
        <div class="card-header">
            <strong>Are you sure you want to delete the industry: <?= Html::encode($model->name) ?>?</strong>
        </div>
        <div class="card-body">
            <p class="mb-3">
                Deleting this industry will also remove the following stocks:
            </p>

            <ul class="mb-4">
                <?php /** @var Stock $stock */
                foreach ($stocks as $stock): ?>
                    <li>
                        <?= Html::encode($stock->company_name) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Card Footer for Buttons -->
        <div class="card-footer d-flex justify-content-end">
            <!-- Cancel Button -->
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary me-2']) ?>

            <!-- Delete Form (inline) -->
            <?= Html::beginForm(['delete', 'id' => $model->id], 'post', [
                'class' => 'd-inline',
                'id' => 'delete-confirmation-form'
            ]) ?>
            <?= Html::hiddenInput('confirm', 1) ?>
            <?= Html::submitButton('Yes, Delete', ['class' => 'btn btn-danger']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>

<?php

use app\helpers\BreadcrumbHelper;
use yii\helpers\Html;

/** @var app\modules\config\models\Sector $model */
/** @var app\modules\config\models\Industry[] $industries */

$this->title = 'Confirm Delete Sector';
$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs('Sectors', 'index', $model, 'Confirm Delete');

?>
<div class="sector-delete-confirm container py-4">

    <div class="card">
        <div class="card-header">
            <strong>Are you sure you want to delete the sector: <?= Html::encode($model->name) ?>?</strong>
        </div>
        <div class="card-body">
            <p class="mb-3">
                Deleting this sector will also remove the following Industries and their Stocks:
            </p>

            <ul class="mb-4">
                <?php foreach ($industries as $industry): ?>
                    <li>
                        <?= Html::encode($industry->name) ?>
                        <ul>
                            <?php foreach ($industry->stocks as $stock): ?>
                                <li><?= Html::encode($stock->name) ?></li>
                            <?php endforeach; ?>
                        </ul>
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

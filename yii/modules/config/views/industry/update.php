<?php

use app\helpers\BreadcrumbHelper;
use app\modules\config\models\Industry;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Industry $model */
/** @var array $sectors */

$this->title = 'Update Industry: ' . $model->name;

$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Industries', 'url' => ['index']],
    ],
    $model,
    'Update'
);

?>
<div class="industry-update container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="border rounded p-4 shadow bg-white mt-4">
                <h3 class="mb-4 text-center"><?= Html::encode($this->title) ?></h3>
                <p class="text-start">Please update the following fields:</p>

                <?= $this->render('_form', [
                    'model' => $model,
                    'sectors' => $sectors,
                ]) ?>
            </div>
        </div>
    </div>
</div>

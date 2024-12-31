<?php

use app\helpers\BreadcrumbHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\config\models\Industry $model */
/** @var array $sectors */

$this->title = 'Create Industry';
$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Configuration', 'url' => null],
        ['label' => 'Industries', 'url' => ['index']],
    ],
    null,
    'Insert'
);

?>
<div class="industry-create container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="border rounded p-4 shadow bg-white mt-4">
                <h3 class="mb-4 text-center"><?= Html::encode($this->title) ?></h3>
                <?= $this->render('_form', [
                    'model' => $model,
                    'sectors' => $sectors,
                ]) ?>
            </div>
        </div>
    </div>
</div>

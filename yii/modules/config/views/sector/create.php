<?php

use app\helpers\BreadcrumbHelper;
use app\modules\config\models\Sector;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Sector $model */

$this->title = 'Create Sector';

$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs(
    [
        ['label' => 'Configuration', 'url' => null],
        ['label' => 'Sectors', 'url' => ['index']],
    ],
    null,
    'Create'
);

?>
<div class="sector-create container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="border rounded p-4 shadow bg-white mt-4">
                <h3 class="mb-4 text-center"><?= Html::encode($this->title) ?></h3>
                <p class="text-start">Please fill out the following fields:</p>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

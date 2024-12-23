<?php

use app\helpers\BreadcrumbHelper;
use app\modules\config\models\Sector;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Sector $model */

$this->title = 'Update Sector: ' . $model->name;

$this->params['breadcrumbs'] = BreadcrumbHelper::generateModelBreadcrumbs('Sectors', 'index', $model, 'Update');

?>
<div class="sector-update container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="border rounded p-4 shadow bg-white mt-4">
                <h3 class="mb-4 text-center"><?= Html::encode($this->title) ?></h3>
                <p class="text-start">Please update the following fields:</p>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
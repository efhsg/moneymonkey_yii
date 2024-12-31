<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\config\models\Industry $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $sectors */

?>

<div class="industry-form">

    <?php $form = ActiveForm::begin(['id' => 'industry-form']); ?>

    <?= $form->field($model, 'sector_id')->dropDownList(
        $sectors,
        ['prompt' => 'Select a Sector'])->label('Sector')
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Industry') ?>

    <div class="form-group mt-4 text-end">
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary me-2']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

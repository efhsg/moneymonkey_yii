<?php

use app\modules\config\models\Sector;
use yii\bootstrap5\{ActiveForm, Html};

/** @var yii\widgets\ActiveForm $form */
/** @var Sector $model */
?>

<?php $form = ActiveForm::begin([
    'id' => 'sector-form',
]); ?>

<?= $form->field($model, 'name')->textInput([
    'placeholder' => 'Enter sector name',
]) ?>

<div class="form-group mt-4 text-end">
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary me-2']) ?>
    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

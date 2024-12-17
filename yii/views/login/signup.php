<?php

use yii\bootstrap5\{
    ActiveForm,
    Html
};

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">

            <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

            <p>Please fill out the following fields to signup:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
            ]); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'Enter your username',
            ]) ?>

            <?= $form->field($model, 'email')->textInput([
                'placeholder' => 'Enter your email',
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Enter your password',
            ]) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary w-100', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
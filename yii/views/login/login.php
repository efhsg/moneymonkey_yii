<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\{
    ActiveForm,
    Html
};

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">

            <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

            <p>Please fill out the following fields to login:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'Enter your username',
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Enter your password',
            ]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check\">{input} {label}</div>\n{error}",
                'class' => 'form-check-input',
            ]) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
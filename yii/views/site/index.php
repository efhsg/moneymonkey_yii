<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Welcome to MoneyMonkey';
?>
<div class="site-index">
    <div class="jumbotron text-center bg-light py-5">
        <img src="<?= Yii::getAlias('@web/images/money-monkey-logo.png') ?>" alt="MoneyMonkey Logo" width="200"
            class="mb-4">
        <h1 class="display-4">MoneyMonkey</h1>
        <p class="lead">Your Automated Stocks Advisor</p>
        <p><?= Html::a('Get Started', ['/site/contact'], ['class' => 'btn btn-primary btn-lg']) ?></p>
    </div>

    <div class="body-content">
        <div class="row text-center h-100">
            <div class="col-lg-4 d-flex flex-column pt-4">
                <h2>Fundamental Analysis</h2>
                <p>Get comprehensive stock ratings based on automated fundamental analysis.</p>
                <div class="mt-auto">
                    <?= Html::a('Learn More &raquo;', ['/site/features'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column pt-4">
                <h2>Portfolio Management</h2>
                <p>Rebalance your portfolio effortlessly using our smart allocation tools.</p>
                <div class="mt-auto">
                    <?= Html::a('Learn More &raquo;', ['/site/features'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column pt-4">
                <h2>Customization</h2>
                <p>Configure your own sectors, industries, data sources, etc.</p>
                <div class="mt-auto">
                    <?= Html::a('Learn More &raquo;', ['/site/features'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\{Breadcrumbs, Html, Nav, NavBar};

$this->beginContent('@app/views/layouts/_base.php'); ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Html::img('@web/images/money-monkey-logo-nav.png', ['alt' => Yii::$app->name, 'height' => 40]) . '&nbsp;&nbsp;&nbsp;' . Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-altdark fixed-top']
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'activateParents' => true,
            'activateItems' => true,
            'items' => [
                [
                    'label' => 'Configuration',
                    'items' => [
                        [
                            'label' => 'Sectors',
                            'url' => ['/config/sector/index'],
                            'active' => (
                                Yii::$app->controller->module->id === 'config'
                                && Yii::$app->controller->id === 'sector'
                            ),
                        ],
                        ['label' => 'Industries', 'url' => ['/config/industry/index']],
                        ['label' => 'Data Sources', 'url' => ['/config/data-source']],
                        ['label' => 'Financial Metrics', 'url' => ['/config/financial-metric']],
                        ['label' => 'Metric Types', 'url' => ['/config/metric-type']],
                    ],
                ],
                [
                    'label' => 'Stock analysis',
                    'url' => ['/site/stock-analysis'],
                ],
                [
                    'label' => 'Portfolio management',
                    'url' => ['/site/portfolio-management'],
                ],
            ],
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => Yii::$app->user->isGuest ? [
                ['label' => 'Signup', 'url' => ['/identity/login/signup']],
                ['label' => 'Login', 'url' => ['/identity/login/login']],
            ] : [
                '<li class="nav-item">'
                . Html::beginForm(['/identity/login/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ],
        ]);

        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container mt-5 pt-5">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

<?php $this->endContent(); ?>
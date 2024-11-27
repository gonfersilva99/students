<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use amnah\yii2\user\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="container">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-sm navbar-light bg-light my-nav',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Subjects', 'url' => '#', 'visible' => Yii::$app->user->can('student') , 'items' => [
                ['label' => 'All Subjects', 'url' => ['/subject/list']],
                ['label' => 'Show subjects', 'url' => ['/subject/list-subscribed']],
            ]],
            ['label' => 'Subjects', 'url' => ['/subject/list'], 'visible' => Yii::$app->user->isGuest],
            ['label' => 'FAQ', 'url' => ['/site/faq']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            ['label' => 'Admin', 'url' => '#',
                'visible' => Yii::$app->user->can('admin'),
                'items' => [
                    ['label' => 'Manage Users', 'url' => ['/user/admin']],
                    ['label' => 'Manage Students', 'url' => ['/student/index']],
                    ['label' => 'Manage Programs', 'url' => ['/program/index']],
                    ['label' => 'Manage Subjects', 'url' => ['/subject/index']],
                ]],
            ['label' => 'Login', 'url' => ['/user/login'], 'visible' => Yii::$app->user->isGuest ],
            ['label' => 'Users', 'url' => '#', 'visible' => !Yii::$app->user->isGuest ,
                'items' => [
                    ['label' => 'Enroll Student', 'url' => ['/student/enroll'], 'visible' => !Yii::$app->user->isGuest && !Yii::$app->user->can('admin')],
                    ['label' => 'Logout (' . Yii::$app->user->displayName . ')',
                        'url' => ['/user/logout'],
                        'linkOptions' => ['data-method' => 'post']],
                ],

            ]
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => ['class' => 'my-bread']
        ]) ?>

        <div class="container">
            <div class="row">
                <div class="col-md-3 colleft">
                    <p>
                        Welcome, User
                    </p>
                    <i class="fas fa-edit fa-8x mt-5"></i>
                </div>
                <div class="col-md-9 main">
                    <div class="container">
                        <div class="row topcontent">
                            <div class="col">
                                <h1><?= $this->title ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col content">
                                <?= Alert::widget() ?>
                                <?= $content ?>
                            </div>
                        </div>
                        <footer class="row footer">
                            <div class="col align-self-lg-center">
                                Copyright © 2021.
                            </div>
                        </footer>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
